-- ========================================================
-- Schema Database: inventory_db (MySQL / phpMyAdmin)
-- Project: Inventory & Sales Management System (UTS Pemrograman Web 2)
-- Framework: Laravel MVC (Eloquent ORM)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `inventory_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `inventory_db`;

-- 1. Tabel users (Akun login pengguna & 3 Role: admin, staff, pembeli)
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'staff', 'pembeli') NOT NULL DEFAULT 'pembeli',
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel user_profiles (Relasi One-to-One dengan users)
CREATE TABLE IF NOT EXISTS `user_profiles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `phone_number` VARCHAR(30) NULL DEFAULT NULL,
    `department` VARCHAR(100) NULL DEFAULT NULL,
    `bio` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel categories (Kategori master produk)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel suppliers (Pemasok / distributor barang)
CREATE TABLE IF NOT EXISTS `suppliers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(100) NULL DEFAULT NULL,
    `phone` VARCHAR(30) NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel products (Data produk elektronik, harga beli, harga jual, stok & soft delete)
CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `purchase_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `selling_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0,
    `minimum_stock` INT NOT NULL DEFAULT 5,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) 
        REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `products_sku_index` (`sku`),
    INDEX `products_stock_minimum_stock_index` (`stock`, `minimum_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel purchases (Header Transaksi Stock-In / Pembelian dari Supplier)
CREATE TABLE IF NOT EXISTS `purchases` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `supplier_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `purchase_number` VARCHAR(50) NOT NULL UNIQUE,
    `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('completed', 'cancelled') NOT NULL DEFAULT 'completed',
    `notes` TEXT NULL DEFAULT NULL,
    `purchased_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) 
        REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `purchases_number_index` (`purchase_number`),
    INDEX `purchases_purchased_at_index` (`purchased_at`),
    INDEX `purchases_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel purchase_items (Detail Item Transaksi Stock-In)
CREATE TABLE IF NOT EXISTS `purchase_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `purchase_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `unit_cost` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) 
        REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) 
        REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `purchase_items_purchase_product_index` (`purchase_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel sales (Header Transaksi Stock-Out / Penjualan ke Pelanggan & Midtrans Payment)
CREATE TABLE IF NOT EXISTS `sales` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `buyer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `sale_number` VARCHAR(50) NOT NULL UNIQUE,
    `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('completed', 'cancelled') NOT NULL DEFAULT 'completed',
    `payment_status` ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'paid',
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'cash',
    `snap_token` VARCHAR(255) NULL DEFAULT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `sold_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `sales_buyer_id_foreign` FOREIGN KEY (`buyer_id`) 
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `sales_number_index` (`sale_number`),
    INDEX `sales_sold_at_index` (`sold_at`),
    INDEX `sales_status_index` (`status`),
    INDEX `sales_buyer_id_index` (`buyer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel sale_items (Detail Item Transaksi Stock-Out)
CREATE TABLE IF NOT EXISTS `sale_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `sale_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) 
        REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) 
        REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `sale_items_sale_product_index` (`sale_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Tabel stock_movements (Audit Trail Mutasi Stok Terperinci)
CREATE TABLE IF NOT EXISTS `stock_movements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `type` ENUM('in', 'out') NOT NULL,
    `quantity` INT NOT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) 
        REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Tabel pivot product_supplier (Relasi Many-to-Many antara products dan suppliers)
CREATE TABLE IF NOT EXISTS `product_supplier` (
    `product_id` BIGINT UNSIGNED NOT NULL,
    `supplier_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`product_id`, `supplier_id`),
    CONSTRAINT `product_supplier_product_id_foreign` FOREIGN KEY (`product_id`) 
        REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `product_supplier_supplier_id_foreign` FOREIGN KEY (`supplier_id`) 
        REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
