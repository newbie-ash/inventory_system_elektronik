# Inventory Management System (Migrasi P4 ke P5 Laravel MVC)

Aplikasi Web **Inventory Management System** yang dibangun berdasarkan modul materi perkuliahan:
- **Pertemuan 4 (P4)**: *Relational Database + Advanced PHP (Problem & Business Logic)*
- **Pertemuan 5 (P5)**: *Laravel Fundamental (Migrasi PHP Native ke Arsitektur MVC Laravel)*

---

## 1. Panduan Menjalankan Aplikasi

### Prasyarat
- PHP >= 8.2 (disarankan PHP 8.4)
- Composer >= 2.0
- Ekstensi PHP: `pdo_sqlite` atau `pdo_mysql`

### Langkah Cepat Menjalankan:
```bash
# 1. Masuk ke direktori project
cd "inventory system fikri"

# 2. Salin environment file & generate application key
cp .env.example .env
php artisan key:generate

# 3. Jalankan migrasi dan seeder data awal
php artisan migrate:fresh --seed

# 4. Jalankan unit & feature tests untuk verifikasi
php artisan test

# 5. Jalankan server lokal
php artisan serve
```

Aplikasi dapat langsung diakses di browser: **`http://127.0.0.1:8000`**

### Akun Uji Coba Bawaan (Hasil Seeder):
| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Administrator** | `admin@inventory.com` | `password` | Akses penuh: Master data Kategori, Supplier, Produk, Pengguna, Transaksi Stok, dan Laporan Eksekutif. |
| **Staff Gudang** | `staff@inventory.com` | `password` | Hak operasional: Melihat katalog produk, mencatat transaksi barang masuk/keluar, dan melihat dashboard. |

---

## 2. Alur Arsitektur Request-Response Laravel (P5 Capaian Pembelajaran)

```
[Browser / Client]
       │  (HTTP Request: GET /products)
       ▼
   [routes/web.php] ──(Middleware: auth, role)──► [ProductController@index]
                                                           │
                                                           ▼ (Eloquent Query)
                                                    [App\Models\Product]
                                                           │
                                                           ▼ (SQL Prepared Statement)
                                                   [(SQLite / MySQL Database)]
                                                           │
                                                           ▼ (Data Hydration)
                                           [resources/views/products/index.blade.php]
                                                           │ (Blade Rendering & Escaping)
                                                           ▼
                                                [HTTP Response (HTML ke Client)]
```

---

## 3. Matriks Komparasi: P4 (PHP Native) vs P5 (Laravel Framework)

| Aspek | Pertemuan 4 (PHP Native) | Pertemuan 5 (Laravel MVC) | Implementasi di Proyek Ini |
|---|---|---|---|
| **Struktur Database** | File mentah `database/schema.sql` | `database/migrations/*.php` | 9 file migration terurut dengan integritas relasi referensial. |
| **Data Dummy** | Insert SQL manual | `database/factories` & `DatabaseSeeder` | Data realistis dengan kategori, produk, transaksi, dan relasi supplier. |
| **Pemisahan Logika** | File procedural terpisah (`public/*.php`) | Model-View-Controller (MVC) | `ProductController`, `StockMovementController`, `ReportController`. |
| **Query Database** | PDO manual dengan `prepare()` & `execute()` | Eloquent ORM + Query Builder | `Product::with('category')->when(...)->paginate(10)`. |
| **Template & View** | `include 'header.php'` dan manual `echo` | Blade Engine (`@extends`, `@section`, `<x-alert>`) | Otomatis sanitasi XSS (`{{ $val }}`) dan komponen UI modular. |
| **Autentikasi & Session** | `session_start()`, `$_SESSION['user']` manual | Laravel Auth Guard + Bcrypt | `Auth::attempt()`, session regeneration, middleware `role`. |
| **Database Transaction** | `$pdo->beginTransaction()` | `DB::beginTransaction()`, `commit()`, `rollBack()` | Digunakan saat mutasi stok bersama pessimistic locking (`lockForUpdate()`). |

---

## 4. Tiga Jenis Relasi Database (Sesuai P4 Slide 5–8)

1. **One-to-One (`users ↔ user_profiles`)**:
   - Satu pengguna memiliki satu profil rinci (nama lengkap, departemen, kontak).
   - Foreign Key pada `user_profiles.user_id` diberi atribut **`UNIQUE`**.
2. **One-to-Many (`categories ──< products`)**:
   - Satu kategori menampung banyak produk.
   - Kolom `products.category_id` merujuk ke `categories.id` (`restrictOnDelete()`).
3. **Many-to-Many (`products >──< suppliers`)**:
   - Relasi junction table: `product_supplier`.
   - Menyimpan pasangan `product_id` dan `supplier_id` dengan tambahan kolom pivot **`supplier_price`** (harga beli supplier).

---

## 5. Aturan Bisnis & Integritas Data (Business Rules)

1. **Stok Tidak Boleh Negatif**:
   - Saat transaksi pengeluaran (Barang Keluar / Out), sistem memvalidasi `quantity <= stock`. Jika melebihi stok fisik, transaksi dibatalkan (`rollBack()`) dan pesan error dikembalikan ke form.
2. **ACID Database Transaction**:
   - Menggunakan `DB::beginTransaction()`. Penambahan/pengurangan stok pada tabel `products` dan pencatatan audit log pada tabel `stock_movements` harus berhasil bersamaan atau gagal bersamaan.
3. **Pessimistic Concurrency Locking**:
   - Menggunakan `Product::lockForUpdate()` saat memproses mutasi stok untuk mencegah *race condition*.
4. **Cegah Penghapusan Sembarangan (Referential Integrity RESTRICT)**:
   - Produk yang sudah memiliki riwayat mutasi stok pada tabel `stock_movements` tidak dapat dihapus, dilindungi oleh database foreign key `RESTRICT` dan validasi controller.
5. **Rumus Konsistensi Stok**:
   $$\text{Stok Akhir} = \text{Stok Awal} + \sum(\text{Barang Masuk}) - \sum(\text{Barang Keluar})$$
6. **Whitelist Sorting & Prepared Search**:
   - Pengurutan data dibatasi hanya pada kolom terdaftar (`name`, `price`, `stock`, `created_at`) dengan direction `ASC`/`DESC` untuk mencegah SQL Injection. Parameter pencarian difilter menggunakan query binding `LIKE`.

---

## 6. Modul Laporan & Query Agregat (P4 Slide 14 & 28)

Dapat diakses di menu **Laporan & Analisis** (`/reports`):
- **Laporan Kategori (Slide 14)**: Menghitung total produk (`COUNT`), total stok (`SUM`), dan rata-rata harga (`AVG`) per kategori produk.
- **Laporan Mutasi Produk (Slide 28)**: Menghitung akumulasi barang masuk (`SUM(CASE WHEN type='in'...)`) dan akumulasi barang keluar per SKU produk.
