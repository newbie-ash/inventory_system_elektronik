<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoController extends Controller
{
    /**
     * Display promotional page with vouchers, active banners, and discounted catalog.
     */
    public function index(Request $request): View
    {
        $activeBanners = PromoBanner::where('is_active', true)->latest()->get();
        $primaryBanner = $activeBanners->first() ?? new PromoBanner([
            'tag' => 'PROMO SPESIAL GAJIAN:',
            'title' => 'Potongan hingga 15% untuk kategori PC & Gadget',
            'description' => 'Salin kode voucher di bawah ini dan gunakan saat checkout untuk potongan harga instan.',
            'button_text' => 'Lihat Semua Voucher',
            'button_url' => '#daftar-voucher',
            'is_active' => true,
        ]);

        // Curated promotional vouchers with discount details & copyable codes
        $vouchers = [
            [
                'code' => 'GAJIAN15',
                'title' => 'Diskon 15% Spesial Gajian',
                'discount' => '15% OFF',
                'badge' => 'Terpopuler',
                'min_spend' => 1500000,
                'max_discount' => 500000,
                'valid_until' => now()->endOfMonth()->translatedFormat('d F Y'),
                'description' => 'Berlaku untuk seluruh kategori Laptop, Gadget, dan Komponen PC.',
            ],
            [
                'code' => 'GEAR50K',
                'title' => 'Potongan Rp 50.000 Aksesoris',
                'discount' => 'Rp 50RB',
                'badge' => 'Gaming Gear',
                'min_spend' => 300000,
                'max_discount' => 50000,
                'valid_until' => now()->addDays(7)->translatedFormat('d F Y'),
                'description' => 'Khusus pembelian mouse, keyboard mekanikal, headset, dan webcam.',
            ],
            [
                'code' => 'ONGKIRFREE',
                'title' => 'Gratis Ongkos Kirim Seluruh Indonesia',
                'discount' => 'FREE ONGKIR',
                'badge' => 'Bebas Ongkir',
                'min_spend' => 500000,
                'max_discount' => 40000,
                'valid_until' => now()->addDays(14)->translatedFormat('d F Y'),
                'description' => 'Potongan biaya pengiriman instan maupun reguler tanpa ribet.',
            ],
            [
                'code' => 'SULTAN1JT',
                'title' => 'Mega Cashback Sultan Rp 1.000.000',
                'discount' => 'Rp 1 JUTA',
                'badge' => 'Eksklusif',
                'min_spend' => 10000000,
                'max_discount' => 1000000,
                'valid_until' => now()->addDays(5)->translatedFormat('d F Y'),
                'description' => 'Khusus rakitan PC workstation, kartu grafis RTX, dan monitor gaming.',
            ],
        ];

        // Promo / discounted products query
        $productQuery = Product::availableForBuyer()->with('category');

        if ($request->filled('category_id')) {
            $productQuery->where('category_id', $request->input('category_id'));
        }

        $products = $productQuery->latest()->paginate(8)->withQueryString();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('stock', '>', 0);
        }])->orderBy('name')->get();

        return view('customer.promo', compact('primaryBanner', 'activeBanners', 'vouchers', 'products', 'categories'));
    }
}
