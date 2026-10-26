<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with Search, Filter, Sorting, and Pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $stockStatus = $request->input('stock_status');

        // Whitelist allowed sort columns
        $allowedSortColumns = ['name', 'price', 'stock', 'created_at'];
        $sort = in_array($request->input('sort'), $allowedSortColumns) ? $request->input('sort') : 'created_at';
        $direction = strtolower($request->input('direction')) === 'asc' ? 'asc' : 'desc';

        $products = Product::with(['category', 'suppliers'])
            // Search by product name or SKU
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            // Filter by Category
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            // Filter by stock status
            ->when($stockStatus === 'low', function ($query) {
                return $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->when($stockStatus === 'empty', function ($query) {
                return $query->where('stock', '<=', 0);
            })
            // Sorting
            ->orderBy($sort, $direction)
            // Pagination with query string retention
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'stockStatus', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'supplier_ids' => ['nullable', 'array'],
            'supplier_ids.*' => ['exists:suppliers,id'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required' => 'Stok awal produk wajib diisi.',
            'stock.min' => 'Jumlah stok tidak boleh bernilai negatif.',
            'min_stock.required' => 'Batas minimum stok wajib diisi.',
            'min_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename = 'product_'.time().'_'.uniqid().'.'.$imageFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/products');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $imageFile->move($destinationPath, $filename);
            $imagePath = 'uploads/products/'.$filename;
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => strtoupper($validated['sku']),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'min_stock' => $validated['min_stock'],
                'image' => $imagePath,
            ]);

            if (! empty($validated['supplier_ids'])) {
                $product->suppliers()->sync($validated['supplier_ids']);
            }

            // Catat transaksi inisialisasi jika stok awal > 0
            if ($validated['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $validated['stock'],
                    'notes' => 'Penerimaan stok awal saat pembuatan produk baru.',
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan produk: '.$e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'suppliers',
            'stockMovements' => function ($query) {
                $query->with('user')->latest()->take(20);
            },
        ]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $selectedSuppliers = $product->suppliers->pluck('id')->toArray();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'selectedSuppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'supplier_ids' => ['nullable', 'array'],
            'supplier_ids.*' => ['exists:suppliers,id'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'min_stock.required' => 'Batas minimum stok wajib diisi.',
            'min_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = $product->image;

        if ($request->boolean('remove_image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $imageFile = $request->file('image');
            $filename = 'product_'.time().'_'.uniqid().'.'.$imageFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/products');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $imageFile->move($destinationPath, $filename);
            $imagePath = 'uploads/products/'.$filename;
        }

        DB::beginTransaction();

        try {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => strtoupper($validated['sku']),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'min_stock' => $validated['min_stock'],
                'image' => $imagePath,
            ]);

            $product->suppliers()->sync($validated['supplier_ids'] ?? []);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal memperbarui produk: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     * Hidden from admin/staff dashboards and buyer catalogs while keeping transaction history intact.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', "Produk {$product->name} berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: '.$e->getMessage());
        }
    }

    /**
     * Display dedicated view for low stock products (Stok Kritis).
     */
    public function lowStock(Request $request): View
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $products = Product::with(['category', 'suppliers'])
            ->lowStock()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->orderBy('stock', 'asc')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $totalLowStock = Product::lowStock()->count();

        return view('products.low_stock', compact('products', 'categories', 'search', 'categoryId', 'totalLowStock'));
    }
}
