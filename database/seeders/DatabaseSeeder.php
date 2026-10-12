<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@inventory.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@inventory.com'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // Seed UserProfile (One-to-One as per P4 Slide 6)
        $admin->profile()->firstOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'Dr. Administrator Utama',
                'phone_number' => '081299887766',
                'department' => 'IT & Operasional Sistem',
                'bio' => 'Penanggung jawab utama database dan administrasi sistem inventaris.',
            ]
        );

        $staff->profile()->firstOrCreate(
            ['user_id' => $staff->id],
            [
                'full_name' => 'Budi Santoso (Staff Lapangan)',
                'phone_number' => '081344556677',
                'department' => 'Gudang & Logistik',
                'bio' => 'Petugas operasional penerimaan barang masuk dan pengeluaran barang keluar.',
            ]
        );

        // 2. Categories
        $categoriesData = [
            ['name' => 'Elektronik & Gadget', 'description' => 'Perangkat komputer, laptop, smartphone, dan aksesoris teknologi.'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'Kebutuhan administrasi seperti kertas, bolpoin, binder, dan ordner.'],
            ['name' => 'Peralatan Gudang', 'description' => 'Perlengkapan packing, lakban, bubble wrap, kardus, dan hand pallet.'],
            ['name' => 'Komponen Sparepart', 'description' => 'Suku cadang mesin, kabel konektor, sekrup, dan komponen mekanik.'],
            ['name' => 'Konsumsi & Pantry', 'description' => 'Kopi, teh, snack, dan perlengkapan konsumsi staf.'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[] = Category::firstOrCreate(['name' => $data['name']], $data);
        }

        // 3. Suppliers
        $suppliersData = [
            [
                'name' => 'PT Surya Digital Teknologi',
                'email' => 'sales@suryadigital.co.id',
                'phone' => '021-5551234',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
            ],
            [
                'name' => 'CV Sumber Kencana Stationery',
                'email' => 'order@sumberkencana.com',
                'phone' => '021-7788990',
                'address' => 'Jl. Pangeran Jayakarta No. 45, Jakarta Pusat',
            ],
            [
                'name' => 'PT Mitra Logistik Perkasa',
                'email' => 'info@mitralogistik.com',
                'phone' => '031-8899123',
                'address' => 'Kawasan Industri Rungkut, Surabaya',
            ],
            [
                'name' => 'CV Mega Teknik Mandiri',
                'email' => 'kontak@megateknik.co.id',
                'phone' => '022-4455667',
                'address' => 'Jl. Soekarno Hatta No. 88, Bandung',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sData['name']], $sData);
        }

        // 4. Products
        $productsData = [
            // Category 0: Elektronik
            [
                'category_id' => $categories[0]->id,
                'name' => 'Laptop Dell Latitude 3420',
                'sku' => 'ELC-LAP-001',
                'description' => 'Intel Core i5, RAM 16GB, SSD 512GB, 14 inch Full HD.',
                'price' => 11500000,
                'stock' => 12,
                'min_stock' => 5,
                'supplier_ids' => [$suppliers[0]->id],
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Mouse Wireless Logitech M330',
                'sku' => 'ELC-MOU-002',
                'description' => 'Silent touch mouse wireless 2.4GHz.',
                'price' => 195000,
                'stock' => 35,
                'min_stock' => 10,
                'supplier_ids' => [$suppliers[0]->id],
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Keyboard Mechanical Keychron K2',
                'sku' => 'ELC-KBD-003',
                'description' => 'Wireless Bluetooth mechanical keyboard Gateron Brown.',
                'price' => 1250000,
                'stock' => 4, // LOW STOCK (<= 5)
                'min_stock' => 5,
                'supplier_ids' => [$suppliers[0]->id],
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Monitor LG 24 Inch IPS 75Hz',
                'sku' => 'ELC-MON-004',
                'description' => 'Full HD 1080p, Borderless design, HDMI & VGA port.',
                'price' => 1650000,
                'stock' => 8,
                'min_stock' => 5,
                'supplier_ids' => [$suppliers[0]->id],
            ],

            // Category 1: ATK
            [
                'category_id' => $categories[1]->id,
                'name' => 'Kertas HVS PaperOne A4 80gsm (Rim)',
                'sku' => 'ATK-KRT-001',
                'description' => 'Kertas photocopy & print putih bersih isi 500 lembar.',
                'price' => 52000,
                'stock' => 60,
                'min_stock' => 15,
                'supplier_ids' => [$suppliers[1]->id],
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Bolpoin Pilot G2 0.5mm Hitam (Pack)',
                'sku' => 'ATK-PEN-002',
                'description' => 'Gel pen berkualitas tinggi pack isi 12 pcs.',
                'price' => 180000,
                'stock' => 3, // LOW STOCK (<= 10)
                'min_stock' => 10,
                'supplier_ids' => [$suppliers[1]->id],
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Ordner Bantex Folio 7cm Biru',
                'sku' => 'ATK-ORD-003',
                'description' => 'Map arsip dokumen kapasitas tebal warna biru.',
                'price' => 32000,
                'stock' => 25,
                'min_stock' => 8,
                'supplier_ids' => [$suppliers[1]->id],
            ],

            // Category 2: Peralatan Gudang
            [
                'category_id' => $categories[2]->id,
                'name' => 'Lakban Bening Daimaru 2 Inch (Roll)',
                'sku' => 'GDG-LKB-001',
                'description' => 'Lakban packing kuat perekat tinggi 100 yard.',
                'price' => 14000,
                'stock' => 100,
                'min_stock' => 20,
                'supplier_ids' => [$suppliers[2]->id],
            ],
            [
                'category_id' => $categories[2]->id,
                'name' => 'Bubble Wrap Roll 1.25m x 50m Hitam',
                'sku' => 'GDG-BBL-002',
                'description' => 'Plastik gelembung tebal untuk proteksi pengiriman ekspedisi.',
                'price' => 145000,
                'stock' => 2, // LOW STOCK (<= 5)
                'min_stock' => 5,
                'supplier_ids' => [$suppliers[2]->id],
            ],
            [
                'category_id' => $categories[2]->id,
                'name' => 'Kardus Packing Box 30x20x15 (Pcs)',
                'sku' => 'GDG-BOX-003',
                'description' => 'Karton tebal corrugated E-flute standar e-commerce.',
                'price' => 4500,
                'stock' => 250,
                'min_stock' => 50,
                'supplier_ids' => [$suppliers[1]->id, $suppliers[2]->id],
            ],

            // Category 3: Sparepart
            [
                'category_id' => $categories[3]->id,
                'name' => 'Kabel LAN Cat6 FTP 305 Meter',
                'sku' => 'SPR-KBL-001',
                'description' => 'Kabel jaringan outdoor pelindung foil tembaga murni.',
                'price' => 1350000,
                'stock' => 6,
                'min_stock' => 3,
                'supplier_ids' => [$suppliers[0]->id, $suppliers[3]->id],
            ],
            [
                'category_id' => $categories[3]->id,
                'name' => 'Power Supply 500W Bronze 80+',
                'sku' => 'SPR-PSU-002',
                'description' => 'Unit catu daya PC efisiensi tinggi stabilizer bawaan.',
                'price' => 580000,
                'stock' => 1, // LOW STOCK (<= 4)
                'min_stock' => 4,
                'supplier_ids' => [$suppliers[3]->id],
            ],
        ];

        foreach ($productsData as $pData) {
            $supplierIds = $pData['supplier_ids'];
            unset($pData['supplier_ids']);

            $product = Product::firstOrCreate(['sku' => $pData['sku']], $pData);

            $syncData = [];
            foreach ($supplierIds as $sId) {
                $syncData[$sId] = ['supplier_price' => round($pData['price'] * 0.85, 2)];
            }
            $product->suppliers()->sync($syncData);

            // Record initial stock movement (in)
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'type' => 'in',
                'quantity' => $product->stock + 5,
                'notes' => 'Stok awal inventaris sistem (Penerimaan pertama)',
                'created_at' => now()->subDays(5),
            ]);

            // Record a sample stock out
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $staff->id,
                'type' => 'out',
                'quantity' => 5,
                'notes' => 'Pengeluaran uji coba operasional gudang',
                'created_at' => now()->subDays(2),
            ]);
        }
    }
}
