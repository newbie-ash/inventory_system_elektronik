<?php

namespace App\Services;

class ShippingService
{
    /**
     * Get available Indonesian delivery destination zones.
     *
     * @return array<string, array{name: string, province: string, base_rate: int, etd: string}>
     */
    public static function getDestinations(): array
    {
        return [
            'jakarta' => ['name' => 'DKI Jakarta', 'province' => 'DKI Jakarta', 'base_rate' => 10000, 'etd' => '1 hari'],
            'bogor' => ['name' => 'Kota / Kab. Bogor', 'province' => 'Jawa Barat', 'base_rate' => 12000, 'etd' => '1-2 hari'],
            'depok' => ['name' => 'Kota Depok', 'province' => 'Jawa Barat', 'base_rate' => 12000, 'etd' => '1-2 hari'],
            'tangerang' => ['name' => 'Tangerang / Tangsel', 'province' => 'Banten', 'base_rate' => 12000, 'etd' => '1-2 hari'],
            'bekasi' => ['name' => 'Kota / Kab. Bekasi', 'province' => 'Jawa Barat', 'base_rate' => 12000, 'etd' => '1-2 hari'],
            'bandung' => ['name' => 'Kota Bandung', 'province' => 'Jawa Barat', 'base_rate' => 15000, 'etd' => '1-2 hari'],
            'semarang' => ['name' => 'Kota Semarang', 'province' => 'Jawa Tengah', 'base_rate' => 18000, 'etd' => '2-3 hari'],
            'yogyakarta' => ['name' => 'DI Yogyakarta', 'province' => 'DI Yogyakarta', 'base_rate' => 18000, 'etd' => '2-3 hari'],
            'surabaya' => ['name' => 'Kota Surabaya', 'province' => 'Jawa Timur', 'base_rate' => 20000, 'etd' => '2-3 hari'],
            'denpasar' => ['name' => 'Denpasar & Badung', 'province' => 'Bali', 'base_rate' => 25000, 'etd' => '2-3 hari'],
            'medan' => ['name' => 'Kota Medan', 'province' => 'Sumatera Utara', 'base_rate' => 35000, 'etd' => '3-4 hari'],
            'palembang' => ['name' => 'Kota Palembang', 'province' => 'Sumatera Selatan', 'base_rate' => 30000, 'etd' => '2-3 hari'],
            'makassar' => ['name' => 'Kota Makassar', 'province' => 'Sulawesi Selatan', 'base_rate' => 40000, 'etd' => '3-4 hari'],
            'balikpapan' => ['name' => 'Kota Balikpapan', 'province' => 'Kalimantan Timur', 'base_rate' => 42000, 'etd' => '3-4 hari'],
            'jayapura' => ['name' => 'Kota Jayapura', 'province' => 'Papua', 'base_rate' => 75000, 'etd' => '4-6 hari'],
        ];
    }

    /**
     * Get list of major courier services.
     *
     * @return array<string, array{code: string, name: string, courier: string, multiplier: float, etd: string, jabodetabek_only?: bool}>
     */
    public static function getCouriers(): array
    {
        return [
            'jne_reg' => [
                'code' => 'jne_reg',
                'name' => 'JNE Reguler',
                'courier' => 'JNE',
                'multiplier' => 1.0,
                'etd' => '2-3 hari kerja',
            ],
            'jne_yes' => [
                'code' => 'jne_yes',
                'name' => 'JNE YES (Yakin Esok Sampai)',
                'courier' => 'JNE',
                'multiplier' => 1.8,
                'etd' => '1 hari kerja',
            ],
            'jnt_ez' => [
                'code' => 'jnt_ez',
                'name' => 'J&T EZ Express',
                'courier' => 'J&T',
                'multiplier' => 1.0,
                'etd' => '2-3 hari kerja',
            ],
            'sicepat_reg' => [
                'code' => 'sicepat_reg',
                'name' => 'SiCepat REG',
                'courier' => 'SiCepat',
                'multiplier' => 0.95,
                'etd' => '2-3 hari kerja',
            ],
            'sicepat_best' => [
                'code' => 'sicepat_best',
                'name' => 'SiCepat BEST (Besok Sampai)',
                'courier' => 'SiCepat',
                'multiplier' => 1.6,
                'etd' => '1 hari kerja',
            ],
            'gosend_instant' => [
                'code' => 'gosend_instant',
                'name' => 'GoSend Instant (Same Day)',
                'courier' => 'GoSend',
                'multiplier' => 2.5,
                'etd' => '3-5 jam tiba',
                'jabodetabek_only' => true,
            ],
        ];
    }

    /**
     * Estimate item weight in grams based on product name/category.
     */
    public static function estimateProductWeight(string $productName): int
    {
        $lower = strtolower($productName);

        if (str_contains($lower, 'laptop') || str_contains($lower, 'macbook')) {
            return 2500;
        }
        if (str_contains($lower, 'monitor') || str_contains($lower, 'display')) {
            return 4500;
        }
        if (str_contains($lower, 'phone') || str_contains($lower, 'smartphone') || str_contains($lower, 'redmagic')) {
            return 500;
        }
        if (str_contains($lower, 'gpu') || str_contains($lower, 'rtx') || str_contains($lower, 'gtx') || str_contains($lower, 'radeon')) {
            return 1800;
        }
        if (str_contains($lower, 'motherboard') || str_contains($lower, 'mainboard')) {
            return 1500;
        }
        if (str_contains($lower, 'tws') || str_contains($lower, 'earbuds') || str_contains($lower, 'ram') || str_contains($lower, 'ssd')) {
            return 200;
        }
        if (str_contains($lower, 'keyboard') || str_contains($lower, 'mouse') || str_contains($lower, 'headset')) {
            return 600;
        }

        return 1000;
    }

    /**
     * Calculate all available shipping rates for a destination and weight.
     *
     * @return array<int, array{code: string, courier: string, service_name: string, cost: float, etd: string, weight_kg: int}>
     */
    public static function calculateRates(string $destinationKey, int $weightGrams = 1000): array
    {
        $destinations = self::getDestinations();
        $dest = $destinations[$destinationKey] ?? $destinations['jakarta'];

        $isJabodetabek = in_array($destinationKey, ['jakarta', 'bogor', 'depok', 'tangerang', 'bekasi'], true);
        $weightKg = max(1, (int) ceil($weightGrams / 1000));

        $results = [];

        foreach (self::getCouriers() as $courierKey => $courier) {
            if (! empty($courier['jabodetabek_only']) && ! $isJabodetabek) {
                continue;
            }

            $calculatedCost = (float) round($dest['base_rate'] * $courier['multiplier'] * $weightKg);

            $results[] = [
                'code' => $courierKey,
                'courier' => $courier['courier'],
                'service_name' => $courier['name'],
                'cost' => $calculatedCost,
                'etd' => $courier['etd'],
                'weight_kg' => $weightKg,
            ];
        }

        return $results;
    }

    /**
     * Get specific shipping cost and details.
     *
     * @return array{valid: bool, courier_name: string, cost: float, destination_name: string, weight_grams: int}
     */
    public static function verifyOption(?string $destinationKey, ?string $courierCode, int $weightGrams = 1000): array
    {
        if (empty($destinationKey) || empty($courierCode)) {
            return [
                'valid' => false,
                'courier_name' => 'Standar Bebas Ongkir',
                'cost' => 0.0,
                'destination_name' => 'Belum dipilih',
                'weight_grams' => $weightGrams,
            ];
        }

        $destinations = self::getDestinations();
        $destinationKey = strtolower($destinationKey);
        $dest = $destinations[$destinationKey] ?? null;

        $couriers = self::getCouriers();
        $courier = $couriers[$courierCode] ?? null;

        if (! $dest || ! $courier) {
            return [
                'valid' => false,
                'courier_name' => 'Standar Toko',
                'cost' => 0.0,
                'destination_name' => $dest['name'] ?? 'Jabodetabek',
                'weight_grams' => $weightGrams,
            ];
        }

        $rates = self::calculateRates($destinationKey, $weightGrams);
        foreach ($rates as $rate) {
            if ($rate['code'] === $courierCode) {
                return [
                    'valid' => true,
                    'courier_name' => $rate['service_name'],
                    'cost' => (float) $rate['cost'],
                    'destination_name' => $dest['name'].', '.$dest['province'],
                    'weight_grams' => $weightGrams,
                ];
            }
        }

        return [
            'valid' => false,
            'courier_name' => 'Standar Toko',
            'cost' => 0.0,
            'destination_name' => $dest['name'],
            'weight_grams' => $weightGrams,
        ];
    }
}
