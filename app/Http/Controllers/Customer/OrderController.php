<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\MidtransService;
use App\Services\PromoService;
use App\Services\ShippingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Display listing of customer's own purchase history with Shopee-style status tabs.
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'all');

        $baseQuery = Sale::where(function ($q) {
            $q->where('buyer_id', Auth::id())
                ->orWhere('user_id', Auth::id());
        });

        // Tab counts for Shopee status bar
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'unpaid' => (clone $baseQuery)->where('status', '!=', 'cancelled')->where('payment_status', 'pending')->where('payment_method', 'midtrans')->count(),
            'packing' => (clone $baseQuery)->where('status', '!=', 'cancelled')->where(function ($q) {
                $q->where('payment_status', 'paid')->orWhere('payment_method', 'cod');
            })->where('shipping_status', 'packing')->count(),
            'shipping' => (clone $baseQuery)->where('status', '!=', 'cancelled')->where('shipping_status', 'shipped')->count(),
            'completed' => (clone $baseQuery)->where('status', '!=', 'cancelled')->where(function ($q) {
                $q->where('shipping_status', 'delivered')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'completed')
                            ->where('payment_status', 'paid')
                            ->whereNotIn('shipping_status', ['packing', 'shipped']);
                    });
            })->count(),
            'cancelled' => (clone $baseQuery)->where(function ($q) {
                $q->where('status', 'cancelled')->orWhere('payment_status', 'failed');
            })->count(),
        ];

        $ordersQuery = (clone $baseQuery)->with(['items.product'])->latest('sold_at');

        if ($tab === 'unpaid') {
            $ordersQuery->where('status', '!=', 'cancelled')
                ->where('payment_status', 'pending')
                ->where('payment_method', 'midtrans');
        } elseif ($tab === 'packing') {
            $ordersQuery->where('status', '!=', 'cancelled')
                ->where(function ($q) {
                    $q->where('payment_status', 'paid')->orWhere('payment_method', 'cod');
                })
                ->where('shipping_status', 'packing');
        } elseif ($tab === 'shipping') {
            $ordersQuery->where('status', '!=', 'cancelled')
                ->where('shipping_status', 'shipped');
        } elseif ($tab === 'completed') {
            $ordersQuery->where('status', '!=', 'cancelled')
                ->where(function ($q) {
                    $q->where('shipping_status', 'delivered')
                        ->orWhere(function ($sub) {
                            $sub->where('status', 'completed')
                                ->where('payment_status', 'paid')
                                ->whereNotIn('shipping_status', ['packing', 'shipped']);
                        });
                });
        } elseif ($tab === 'cancelled') {
            $ordersQuery->where(function ($q) {
                $q->where('status', 'cancelled')->orWhere('payment_status', 'failed');
            });
        }

        $orders = $ordersQuery->paginate(8)->withQueryString();

        return view('customer.orders.index', compact('orders', 'tab', 'counts'));
    }

    /**
     * Store new customer order with multi-item and Midtrans Snap generation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['nullable', 'string', 'in:midtrans,cod'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'destination' => ['nullable', 'string', 'max:100'],
            'courier' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Keranjang belanja Anda masih kosong.',
            'items.*.quantity.min' => 'Kuantitas pesanan minimal 1 unit.',
        ]);

        $paymentMethod = $validated['payment_method'] ?? 'midtrans';
        $destination = $validated['destination'] ?? null;
        $courier = $validated['courier'] ?? null;
        $sessionPromo = $request->session()->get('applied_promo');
        $rawPromoCode = $validated['promo_code'] ?? ($sessionPromo['code'] ?? null);

        $sale = DB::transaction(function () use ($validated, $paymentMethod, $rawPromoCode, $destination, $courier) {
            $prefix = 'SL-'.date('Ymd').'-';
            $countToday = Sale::whereDate('created_at', today())->count() + 1;
            $saleNumber = $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'buyer_id' => Auth::id(),
                'sale_number' => $saleNumber,
                'subtotal' => 0,
                'discount_amount' => 0,
                'shipping_cost' => 0,
                'promo_code' => null,
                'total' => 0,
                'status' => 'completed',
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'shipping_status' => $paymentMethod === 'cod' ? 'packing' : 'pending',
                'notes' => $validated['notes'] ?? ($paymentMethod === 'cod' ? 'Pesanan Bayar di Tempat (COD)' : 'Pesanan Pembeli Online'),
                'sold_at' => now(),
            ]);

            $itemsSubtotal = 0;
            $totalWeight = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$product->name} tidak mencukupi (Tersisa: {$product->stock}, Dipesan: {$qty}).",
                    ]);
                }

                $totalWeight += (ShippingService::estimateProductWeight($product->name) * $qty);

                $unitPrice = (float) ($product->selling_price > 0 ? $product->selling_price : $product->price);
                $subtotal = round($unitPrice * $qty, 2);

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Atomically decrement stock
                $product->decrement('stock', $qty);
                $product->refresh()->notifyIfLowStock();

                // Record audit trail
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $qty,
                    'notes' => "Pesanan Online Pembeli #{$sale->sale_number}",
                ]);

                $itemsSubtotal += $subtotal;
            }

            if ($totalWeight === 0) {
                $totalWeight = 1000;
            }

            // Calculate Shipping Cost if destination & courier are specified
            $shippingCost = 0.0;
            $shippingCourier = null;
            $shippingDestination = null;

            if (! empty($destination) && ! empty($courier)) {
                $shippingInfo = ShippingService::verifyOption($destination, $courier, $totalWeight);
                $shippingCost = (float) $shippingInfo['cost'];
                $shippingCourier = $shippingInfo['courier_name'];
                $shippingDestination = $shippingInfo['destination_name'];
            }

            // Calculate Promo Discount
            $discountAmount = 0.0;
            $appliedCode = null;
            if (! empty($rawPromoCode)) {
                $promoCheck = PromoService::validate($rawPromoCode, $itemsSubtotal);
                if ($promoCheck['valid']) {
                    $discountAmount = (float) $promoCheck['discount'];
                    $appliedCode = $promoCheck['code'];
                }
            }

            $finalTotal = max(0.0, $itemsSubtotal - $discountAmount + $shippingCost);

            $sale->update([
                'subtotal' => $itemsSubtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'shipping_courier' => $shippingCourier,
                'shipping_destination' => $shippingDestination,
                'shipping_weight' => $totalWeight,
                'promo_code' => $appliedCode,
                'total' => $finalTotal,
            ]);

            return $sale;
        });

        // Kosongkan keranjang belanja & voucher setelah checkout sukses
        $request->session()->forget(['cart', 'applied_promo']);

        if ($paymentMethod === 'cod') {
            return redirect()->route('customer.orders.show', $sale)
                ->with('success', "Pesanan #{$sale->sale_number} berhasil dibuat dengan metode COD (Bayar di Tempat). Siapkan uang tunai saat kurir tiba.");
        }

        // Generate Midtrans Snap token dan arahkan ke Halaman Pembayaran Khusus
        $this->midtransService->createSnapToken($sale);

        return redirect()->route('customer.orders.payment', $sale)
            ->with('success', "Pesanan #{$sale->sale_number} berhasil dibuat. Silakan selesaikan pembayaran online Anda.");
    }

    /**
     * Buyer cancels an unpaid or un-shipped order, restoring inventory stock.
     */
    public function cancel(Request $request, Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang membatalkan pesanan ini.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Pesanan ini sudah dalam status dibatalkan.');
        }

        if (in_array($sale->shipping_status, ['shipped', 'delivered'])) {
            return back()->with('error', 'Pesanan yang sedang dalam proses pengiriman atau telah sampai tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($sale) {
            $sale->load('items.product');

            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'notes' => "Pembatalan Pesanan #{$sale->sale_number} oleh pembeli",
                    ]);
                }
            }

            $newPaymentStatus = $sale->payment_status === 'paid' ? 'refunded' : 'failed';

            $sale->update([
                'status' => 'cancelled',
                'payment_status' => $newPaymentStatus,
                'shipping_status' => 'cancelled',
            ]);
        });

        return redirect()->route('customer.orders.index', ['tab' => 'cancelled'])
            ->with('success', "Pesanan #{$sale->sale_number} berhasil dibatalkan. Stok barang telah dikembalikan ke inventaris.");
    }

    /**
     * Switch an unpaid Midtrans order to Cash on Delivery (COD).
     */
    public function switchToCod(Request $request, Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($sale->status === 'cancelled' || $sale->payment_status === 'paid') {
            return back()->with('error', 'Pesanan tidak dapat diubah ke Bayar di Tempat (COD).');
        }

        $sale->update([
            'payment_method' => 'cod',
            'shipping_status' => 'packing',
            'notes' => ($sale->notes ? $sale->notes.' | ' : '').'Diubah ke Bayar di Tempat (COD)',
        ]);

        return redirect()->route('customer.orders.show', $sale)
            ->with('success', "Metode pembayaran pesanan #{$sale->sale_number} berhasil diubah ke COD (Bayar di Tempat). Siapkan uang tunai saat kurir tiba.");
    }

    /**
     * Apply or update a promotional voucher code on an unpaid order.
     */
    public function applyPromo(Request $request, Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak.');
        }

        if ($sale->payment_status === 'paid' || $sale->status === 'cancelled') {
            return redirect()->route('customer.orders.payment', $sale)
                ->with('error', 'Voucher tidak dapat diterapkan pada pesanan ini.');
        }

        $request->validate([
            'promo_code' => ['required', 'string', 'max:50'],
        ]);

        $itemsSubtotal = $sale->subtotal > 0 ? (float) $sale->subtotal : (float) $sale->total;
        $promoResult = PromoService::validate($request->promo_code, $itemsSubtotal);

        if (! $promoResult['valid']) {
            return redirect()->route('customer.orders.payment', $sale)
                ->with('error', $promoResult['message']);
        }

        $discount = (float) $promoResult['discount'];
        $finalTotal = max(0.0, $itemsSubtotal - $discount);

        $sale->update([
            'subtotal' => $itemsSubtotal,
            'discount_amount' => $discount,
            'promo_code' => $promoResult['code'],
            'total' => $finalTotal,
        ]);

        // Recreate Snap Token with new total
        $this->midtransService->createSnapToken($sale);

        return redirect()->route('customer.orders.payment', $sale)
            ->with('success', "Voucher {$promoResult['code']} berhasil digunakan! Hemat Rp ".number_format($discount, 0, ',', '.'));
    }

    /**
     * Display details of a specific customer order.
     */
    public function show(Request $request, Sale $sale): View
    {
        // Enforce server-side authorization: buyer can only view their own order
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki akses ke pesanan ini.');
        }

        $sale->load(['items.product', 'buyer', 'user']);

        // Check if returning from Midtrans redirect or actively polling payment status
        if ($sale->payment_status !== 'paid') {
            $trxStatus = $request->query('transaction_status');
            if ($trxStatus) {
                $statusData = $this->midtransService->parseNotificationStatus($trxStatus, $request->query('fraud_status'));
                if ($statusData['payment_status'] === 'paid') {
                    $sale->update([
                        'payment_status' => 'paid',
                        'shipping_status' => $sale->shipping_status === 'pending' ? 'packing' : $sale->shipping_status,
                    ]);
                    session()->flash('success', 'Pembayaran Midtrans berhasil diverifikasi (Lunas)!');
                }
            }

            // If still pending and has a real token (not mock), check directly with Midtrans API
            if ($sale->payment_status !== 'paid' && ! empty($sale->snap_token) && ! str_starts_with($sale->snap_token, 'SNAP-MOCK-')) {
                $statusResponse = $this->midtransService->checkStatus($sale->sale_number);
                if ($statusResponse && isset($statusResponse['transaction_status'])) {
                    $parsed = $this->midtransService->parseNotificationStatus(
                        $statusResponse['transaction_status'],
                        $statusResponse['fraud_status'] ?? null
                    );
                    if ($parsed['payment_status'] === 'paid') {
                        $sale->update([
                            'payment_status' => 'paid',
                            'shipping_status' => $sale->shipping_status === 'pending' ? 'packing' : $sale->shipping_status,
                        ]);
                        session()->flash('success', 'Pembayaran Midtrans berhasil diverifikasi (Lunas)!');
                    }
                }
            }
        }

        if ((empty($sale->snap_token) || str_starts_with($sale->snap_token, 'SNAP-MOCK-')) && $sale->payment_status !== 'paid') {
            $this->midtransService->createSnapToken($sale);
            $sale->refresh();
        }

        $clientKey = $this->midtransService->getClientKey();
        $snapUrl = $this->midtransService->getSnapUrl();

        return view('customer.orders.show', compact('sale', 'clientKey', 'snapUrl'));
    }

    /**
     * Display dedicated payment page for an order.
     */
    public function payment(Request $request, Sale $sale): View|RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki akses ke transaksi ini.');
        }

        // If already paid or using COD, payment screen is not needed, send to audit/order page
        if ($sale->payment_status === 'paid' || $sale->payment_method === 'cod') {
            return redirect()->route('customer.orders.show', $sale);
        }

        $sale->load(['items.product', 'buyer', 'user']);

        if (empty($sale->snap_token) || str_starts_with($sale->snap_token, 'SNAP-MOCK-')) {
            $this->midtransService->createSnapToken($sale);
            $sale->refresh();
        }

        $clientKey = $this->midtransService->getClientKey();
        $snapUrl = $this->midtransService->getSnapUrl();

        return view('customer.orders.payment', compact('sale', 'clientKey', 'snapUrl'));
    }

    /**
     * Sandbox demo payment simulation helper.
     */
    public function simulatePayment(Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $sale->update([
            'payment_status' => 'paid',
            'shipping_status' => 'packing',
        ]);

        return redirect()->route('customer.orders.show', $sale)
            ->with('success', 'Pembayaran Midtrans Sandbox Berhasil Dikonfirmasi (Lunas).');
    }

    /**
     * Buyer confirms delivery of shipped order ("Pesanan Diterima").
     */
    public function confirmDelivery(Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $updateData = [
            'shipping_status' => 'delivered',
            'status' => 'completed',
        ];

        if ($sale->payment_method === 'cod') {
            $updateData['payment_status'] = 'paid';
        }

        $sale->update($updateData);

        return back()->with('success', "Pesanan #{$sale->sale_number} telah Anda konfirmasi diterima. Terima kasih!");
    }

    /**
     * Demo / test simulation helper to advance shipping status.
     */
    public function advanceShipping(Sale $sale): RedirectResponse
    {
        if ($sale->buyer_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($sale->shipping_status === 'pending' || $sale->shipping_status === 'packing') {
            $sale->update(['shipping_status' => 'shipped']);
            $msg = "Status pengiriman diubah ke: 'Dikirim' (Kurir sedang menuju alamat penerima).";
        } elseif ($sale->shipping_status === 'shipped') {
            $updateData = ['shipping_status' => 'delivered', 'status' => 'completed'];
            if ($sale->payment_method === 'cod') {
                $updateData['payment_status'] = 'paid';
            }
            $sale->update($updateData);
            $msg = "Status pengiriman diubah ke: 'Selesai' (Paket telah sampai di tujuan).";
        } else {
            $msg = 'Pesanan ini sudah selesai.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Download official PDF sales invoice for customer order with Kop Surat.
     */
    public function downloadInvoicePdf(Sale $sale): Response
    {
        if ($sale->buyer_id !== Auth::id() && $sale->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Akses invoice tidak diizinkan.');
        }

        $sale->load(['user', 'buyer.profile', 'items.product']);

        $logoPath = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('pdf.invoice', compact('sale', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Invoice-{$sale->sale_number}.pdf");
    }
}
