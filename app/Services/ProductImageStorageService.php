<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageStorageService
{
    /**
     * Store an uploaded product image both in local public assets and in cloud storage if enabled.
     */
    public static function store(UploadedFile $file, string $sku, string $name): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $slug = Str::slug($name);
        $filename = 'product_'.time().'_'.uniqid().'.'.$ext;

        // 1. Save to public/uploads/products
        $uploadDir = public_path('uploads/products');
        if (! File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }
        $targetUpload = $uploadDir.DIRECTORY_SEPARATOR.$filename;
        try {
            $file->move($uploadDir, $filename);
        } catch (\Throwable $e) {
            @copy($file->getRealPath(), $targetUpload);
            if (! File::exists($targetUpload)) {
                File::put($targetUpload, File::get($file->getRealPath()));
            }
        }

        // 2. Mirror into permanent git-trackable asset public/images/products/
        $assetDir = public_path('images/products');
        if (! File::isDirectory($assetDir)) {
            File::makeDirectory($assetDir, 0777, true, true);
        }

        $canonicalAsset = 'images/products/'.$slug.'.'.$ext;
        $canonicalFullPath = public_path($canonicalAsset);

        if (File::exists($targetUpload)) {
            @copy($targetUpload, $canonicalFullPath);
            if (! empty($sku)) {
                @copy($targetUpload, $assetDir.DIRECTORY_SEPARATOR.strtolower($sku).'.'.$ext);
            }
        }

        // 3. Upload to Cloud Object Storage (S3 / Cloudflare R2) if configured
        $cloudDisk = config('filesystems.default');
        if (in_array($cloudDisk, ['s3', 'r2']) && File::exists($canonicalFullPath)) {
            try {
                Storage::disk($cloudDisk)->putFileAs('products', new \Illuminate\Http\File($canonicalFullPath), $slug.'.'.$ext, 'public');
            } catch (\Throwable $e) {
                Log::warning("Gagal sinkronisasi gambar produk ke cloud storage disk ({$cloudDisk}): ".$e->getMessage());
            }
        }

        return $canonicalAsset;
    }

    /**
     * Delete product image from local public directories and cloud disk.
     */
    public static function delete(?string $imagePath, ?string $sku = null, ?string $name = null): void
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            @File::delete(public_path($imagePath));
        }

        if ($sku) {
            $skuPath = public_path('images/products/'.strtolower($sku).'.png');
            if (File::exists($skuPath)) {
                @File::delete($skuPath);
            }
        }

        if ($name) {
            $slugPath = public_path('images/products/'.Str::slug($name).'.png');
            if (File::exists($slugPath)) {
                @File::delete($slugPath);
            }
        }

        $cloudDisk = config('filesystems.default');
        if (in_array($cloudDisk, ['s3', 'r2']) && $imagePath) {
            try {
                Storage::disk($cloudDisk)->delete($imagePath);
                if ($name) {
                    Storage::disk($cloudDisk)->delete('products/'.Str::slug($name).'.png');
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal menghapus file dari cloud storage disk ({$cloudDisk}): ".$e->getMessage());
            }
        }
    }

    /**
     * Sync all local asset images in public/images/products to the configured cloud storage disk.
     */
    public static function syncAllToCloud(?string $disk = null): array
    {
        $targetDisk = $disk ?? config('filesystems.default');
        $assetDir = public_path('images/products');

        if (! File::isDirectory($assetDir)) {
            return ['synced' => 0, 'files' => []];
        }

        $files = File::files($assetDir);
        $syncedFiles = [];

        foreach ($files as $file) {
            $relativePath = 'images/products/'.$file->getFilename();
            try {
                Storage::disk($targetDisk)->putFileAs(
                    'images/products',
                    new \Illuminate\Http\File($file->getRealPath()),
                    $file->getFilename(),
                    'public'
                );
                $syncedFiles[] = $relativePath;
            } catch (\Throwable $e) {
                Log::warning("Gagal sinkronisasi {$file->getFilename()} ke {$targetDisk}: ".$e->getMessage());
            }
        }

        return [
            'disk' => $targetDisk,
            'synced' => count($syncedFiles),
            'files' => $syncedFiles,
        ];
    }
}
