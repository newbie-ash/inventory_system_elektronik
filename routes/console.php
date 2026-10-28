<?php

use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('inventory:check-low-stock', function () {
    $products = Product::lowStock()->get();
    $count = 0;
    foreach ($products as $product) {
        if ($product->notifyIfLowStock()) {
            $count++;
        }
    }
    $this->info("Pengecekan stok kritis selesai. {$count} notifikasi stok kritis baru diterbitkan.");
})->purpose('Cek stok kritis produk dan kirim notifikasi ke Admin & Staff');
