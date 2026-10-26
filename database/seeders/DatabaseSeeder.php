<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo accounts and electronics data.
     */
    public function run(): void
    {
        // 1. Demo Accounts (3 Roles: Admin, Staff, Pembeli)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Utama (Owner)',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Budi Santoso (Staff Gudang & Kasir)',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        $pembeli = User::firstOrCreate(
            ['email' => 'pembeli@example.com'],
            [
                'name' => 'Ahmad Fauzi (Pelanggan)',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
            ]
        );

        // Keep legacy test accounts for backwards compatibility
        User::firstOrCreate(
            ['email' => 'admin@inventory.com'],
            ['name' => 'Admin Legacy', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        User::firstOrCreate(
            ['email' => 'staff@inventory.com'],
            ['name' => 'Staff Legacy', 'password' => Hash::make('password'), 'role' => 'staff']
        );

        // Profiles
        $admin->profile()->firstOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'Dr. Administrator Utama',
                'phone_number' => '081299887766',
                'department' => 'IT & Operasional Sistem',
                'bio' => 'Penanggung jawab utama database dan manajemen inventaris.',
            ]
        );

        $staff->profile()->firstOrCreate(
            ['user_id' => $staff->id],
            [
                'full_name' => 'Budi Santoso',
                'phone_number' => '081344556677',
                'department' => 'Gudang & Kasir',
                'bio' => 'Petugas operasional penerimaan barang masuk dan pengeluaran barang kasir.',
            ]
        );

        $pembeli->profile()->firstOrCreate(
            ['user_id' => $pembeli->id],
            [
                'full_name' => 'Ahmad Fauzi',
                'phone_number' => '085711223344',
                'department' => 'Customer',
                'bio' => 'Pelanggan setia produk elektronik dan perangkat gadget.',
            ]
        );

        // 2. Electronic Categories
        $categoriesData = [
            ['name' => 'Laptop & Komputer', 'description' => 'Laptop bisnis, ultrabook, desktop PC, dan all-in-one.'],
            ['name' => 'Periferal & Aksesoris', 'description' => 'Keyboard mechanical, mouse wireless, webcam, dan headset.'],
            ['name' => 'Monitor & Display', 'description' => 'Monitor gaming, monitor IPS kerja, panel display 4K, dan bracket.'],
            ['name' => 'Komponen & Storage', 'description' => 'SSD NVMe, RAM DDR4/DDR5, Power Supply (PSU), dan motherboard.'],
            ['name' => 'Jaringan & Kabel', 'description' => 'Router WiFi 6, kabel LAN Cat6, switch hub, dan converter adaptor.'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[] = Category::firstOrCreate(['name' => $data['name']], $data);
        }

        // 3. Electronic Suppliers
        $suppliersData = [
            [
                'name' => 'PT Surya Digital Distribusi',
                'email' => 'sales@suryadigital.co.id',
                'phone' => '021-5551234',
                'address' => 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur',
            ],
            [
                'name' => 'CV Mitra Periferal Nusantara',
                'email' => 'order@mitraperiferal.com',
                'phone' => '021-7788990',
                'address' => 'Jl. Pangeran Jayakarta No. 45, Jakarta Pusat',
            ],
            [
                'name' => 'PT Mega Display Indonesia',
                'email' => 'info@megadisplay.co.id',
                'phone' => '031-8899123',
                'address' => 'Kawasan Industri Rungkut Megah, Surabaya',
            ],
            [
                'name' => 'CV Komponen Hardware Prima',
                'email' => 'kontak@komponenprima.com',
                'phone' => '022-4455667',
                'address' => 'Jl. Soekarno Hatta No. 88, Bandung',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sData['name']], $sData);
        }

        // 4. Electronic Products with Realistic Pricing & Stocks
        $productsData = [
            [
                'category_id' => $categories[0]->id,
                'name' => 'Laptop Dell Latitude 3420 Core i5',
                'sku' => 'ELC-LAP-001',
                'description' => 'Intel Core i5-1135G7, RAM 16GB, SSD 512GB NVMe, 14 Inch Full HD Anti-Glare.',
                'purchase_price' => 9200000,
                'selling_price' => 11500000,
                'stock' => 12,
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[0]->id],
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Lenovo ThinkPad E14 Gen 4 Ryzen 5',
                'sku' => 'ELC-LAP-002',
                'description' => 'AMD Ryzen 5 5625U, RAM 16GB, SSD 512GB, Fingerprint reader, Military-grade durability.',
                'purchase_price' => 8800000,
                'selling_price' => 10800000,
                'stock' => 8,
                'minimum_stock' => 4,
                'supplier_ids' => [$suppliers[0]->id],
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Mouse Wireless Logitech M330 Silent',
                'sku' => 'ELC-MOU-002',
                'description' => 'Silent touch mouse wireless 2.4GHz USB nano receiver, battery life up to 24 months.',
                'purchase_price' => 145000,
                'selling_price' => 195000,
                'stock' => 35,
                'minimum_stock' => 10,
                'supplier_ids' => [$suppliers[1]->id],
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Keyboard Mechanical Keychron K2 Wireless',
                'sku' => 'ELC-KBD-003',
                'description' => 'Wireless Bluetooth mechanical keyboard Gateron Brown switch, RGB backlit 84 keys.',
                'purchase_price' => 980000,
                'selling_price' => 1250000,
                'stock' => 4, // CRITICAL LOW STOCK (<= 5)
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[1]->id],
            ],
            [
                'category_id' => $categories[2]->id,
                'name' => 'Monitor LG 24 Inch IPS 75Hz Borderless',
                'sku' => 'ELC-MON-004',
                'description' => 'Full HD 1080p, AMD FreeSync, Flicker Safe, Reader Mode, HDMI & VGA port.',
                'purchase_price' => 1350000,
                'selling_price' => 1650000,
                'stock' => 10,
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[2]->id],
            ],
            [
                'category_id' => $categories[2]->id,
                'name' => 'Monitor Gaming ASUS TUF 27 Inch 165Hz',
                'sku' => 'ELC-MON-005',
                'description' => 'IPS Panel 1ms MPRT, Extreme Low Motion Blur, G-SYNC Compatible, HDR10.',
                'purchase_price' => 2800000,
                'selling_price' => 3450000,
                'stock' => 3, // CRITICAL LOW STOCK (<= 4)
                'minimum_stock' => 4,
                'supplier_ids' => [$suppliers[2]->id],
            ],
            [
                'category_id' => $categories[3]->id,
                'name' => 'SSD Samsung 980 NVMe M.2 1TB',
                'sku' => 'ELC-SSD-006',
                'description' => 'PCIe 3.0 x4 NVMe 1.4, Read speed up to 3500MB/s, Write speed up to 3000MB/s.',
                'purchase_price' => 1100000,
                'selling_price' => 1380000,
                'stock' => 18,
                'minimum_stock' => 6,
                'supplier_ids' => [$suppliers[3]->id],
            ],
            [
                'category_id' => $categories[3]->id,
                'name' => 'Power Supply Corsair CV550 550W 80+ Bronze',
                'sku' => 'SPR-PSU-002',
                'description' => 'Continuous power delivery 80 PLUS Bronze certified, 120mm low-noise cooling fan.',
                'purchase_price' => 480000,
                'selling_price' => 620000,
                'stock' => 2, // CRITICAL LOW STOCK (<= 4)
                'minimum_stock' => 4,
                'supplier_ids' => [$suppliers[3]->id],
            ],
            [
                'category_id' => $categories[4]->id,
                'name' => 'Router WiFi 6 TP-Link Archer AX12 Gigabit',
                'sku' => 'NET-RTR-001',
                'description' => 'Dual-Band Wi-Fi 6 up to 1.5 Gbps (1201 Mbps 5GHz + 300 Mbps 2.4GHz), 4 Antennas.',
                'purchase_price' => 420000,
                'selling_price' => 540000,
                'stock' => 15,
                'minimum_stock' => 5,
                'supplier_ids' => [$suppliers[0]->id],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $supplierIds = $pData['supplier_ids'];
            unset($pData['supplier_ids']);

            $product = Product::firstOrCreate(['sku' => $pData['sku']], $pData);
            $product->price = $pData['selling_price'];
            $product->min_stock = $pData['minimum_stock'];
            $product->save();

            $syncData = [];
            foreach ($supplierIds as $sId) {
                $syncData[$sId] = ['supplier_price' => $pData['purchase_price']];
            }
            $product->suppliers()->sync($syncData);

            $createdProducts[] = $product;
        }

        // 5. Initial Sample Purchase (Stock-In Header & Items)
        $purchase = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-20261020-0001'],
            [
                'supplier_id' => $suppliers[0]->id,
                'user_id' => $admin->id,
                'total' => 27200000,
                'status' => 'completed',
                'notes' => 'Penerimaan stok batch 1 laptop dan router dari PT Surya Digital.',
                'purchased_at' => now()->subDays(3),
            ]
        );

        $purchase->items()->firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $createdProducts[0]->id],
            ['quantity' => 2, 'unit_cost' => 9200000, 'subtotal' => 18400000]
        );
        $purchase->items()->firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $createdProducts[1]->id],
            ['quantity' => 1, 'unit_cost' => 8800000, 'subtotal' => 8800000]
        );

        // 6. Initial Sample Sale (Stock-Out Header & Items)
        $sale = Sale::firstOrCreate(
            ['sale_number' => 'SL-20261021-0001'],
            [
                'user_id' => $staff->id,
                'buyer_id' => $pembeli->id,
                'total' => 11695000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'midtrans',
                'snap_token' => 'SNAP-MOCK-SAMPLE-PAID-TOKEN',
                'notes' => 'Pembelian unit laptop dan mouse oleh pelanggan Ahmad Fauzi.',
                'sold_at' => now()->subDays(2),
            ]
        );

        $sale->items()->firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $createdProducts[0]->id],
            ['quantity' => 1, 'unit_price' => 11500000, 'subtotal' => 11500000]
        );
        $sale->items()->firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $createdProducts[2]->id],
            ['quantity' => 1, 'unit_price' => 195000, 'subtotal' => 195000]
        );

        // 7. Initial Audit Trail in StockMovements
        StockMovement::firstOrCreate(
            ['notes' => 'Inisialisasi stok awal sistem inventaris elektronik'],
            [
                'product_id' => $createdProducts[0]->id,
                'user_id' => $admin->id,
                'type' => 'in',
                'quantity' => 12,
            ]
        );

        // 8. Default Active Promo Banner
        PromoBanner::firstOrCreate(
            ['id' => 1],
            [
                'tag' => 'PROMO SPESIAL GAJIAN:',
                'title' => 'Potongan hingga 15% untuk kategori PC & Gadget',
                'description' => 'Dapatkan penawaran terbatas untuk produk elektronik & hardware pilihan minggu ini.',
                'button_text' => 'Klaim Voucher',
                'button_url' => '#katalog-grid',
                'is_active' => true,
            ]
        );
    }
}
