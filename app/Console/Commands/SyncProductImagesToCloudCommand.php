<?php

namespace App\Console\Commands;

use App\Services\ProductImageStorageService;
use Illuminate\Console\Command;

class SyncProductImagesToCloudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:sync-images-cloud {--disk= : Target cloud disk (s3, r2, or public)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi seluruh aset foto produk lokal ke Cloud Object Storage (S3 / Cloudflare R2)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = $this->option('disk') ?? config('filesystems.default');

        $this->info("Memulai sinkronisasi foto produk ke cloud storage disk: [{$disk}]...");

        $result = ProductImageStorageService::syncAllToCloud($disk);

        if ($result['synced'] > 0) {
            $this->info("Berhasil mengunggah {$result['synced']} berkas gambar ke disk [{$result['disk']}].");
            foreach ($result['files'] as $file) {
                $this->line("  <info>&check;</info> {$file}");
            }
        } else {
            $this->warn("Tidak ada berkas yang disinkronisasi ke disk [{$disk}]. Pastikan direktori public/images/products memiliki gambar.");
        }

        return self::SUCCESS;
    }
}
