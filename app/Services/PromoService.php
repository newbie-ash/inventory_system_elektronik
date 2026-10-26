<?php

namespace App\Services;

class PromoService
{
    /**
     * Get list of active promotional promo codes.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getActivePromos(): array
    {
        return [
            'GAJIAN15' => [
                'code' => 'GAJIAN15',
                'title' => 'Diskon 15% Spesial Gajian',
                'type' => 'percentage',
                'value' => 15,
                'min_spend' => 1500000,
                'max_discount' => 500000,
                'description' => 'Diskon 15% hingga Rp 500.000 (Min. Belanja Rp 1.500.000)',
            ],
            'GEAR50K' => [
                'code' => 'GEAR50K',
                'title' => 'Potongan Rp 50.000 Aksesoris',
                'type' => 'fixed',
                'value' => 50000,
                'min_spend' => 300000,
                'max_discount' => 50000,
                'description' => 'Potongan Rp 50.000 (Min. Belanja Rp 300.000)',
            ],
            'ONGKIRFREE' => [
                'code' => 'ONGKIRFREE',
                'title' => 'Gratis Ongkos Kirim Seluruh Indonesia',
                'type' => 'fixed',
                'value' => 40000,
                'min_spend' => 500000,
                'max_discount' => 40000,
                'description' => 'Potongan ongkir Rp 40.000 (Min. Belanja Rp 500.000)',
            ],
            'CTRLR10' => [
                'code' => 'CTRLR10',
                'title' => 'Diskon 10% Member Ctrl+R',
                'type' => 'percentage',
                'value' => 10,
                'min_spend' => 200000,
                'max_discount' => 250000,
                'description' => 'Diskon 10% hingga Rp 250.000 (Min. Belanja Rp 200.000)',
            ],
        ];
    }

    /**
     * Validate and calculate discount for a promo code.
     *
     * @return array{valid: bool, discount: float, message: string, code: ?string}
     */
    public static function validate(string $code, float $subtotal): array
    {
        $normalized = strtoupper(trim($code));
        $promos = self::getActivePromos();

        if (! isset($promos[$normalized])) {
            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => "Kode promo '{$code}' tidak valid atau sudah kadaluarsa.",
                'code' => null,
            ];
        }

        $promo = $promos[$normalized];

        if ($subtotal < (float) $promo['min_spend']) {
            $formattedMin = number_format((float) $promo['min_spend'], 0, ',', '.');

            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => "Kode promo {$promo['code']} membutuhkan minimal belanja Rp {$formattedMin}.",
                'code' => null,
            ];
        }

        $discount = 0.0;
        if ($promo['type'] === 'percentage') {
            $discount = round(($subtotal * (float) $promo['value']) / 100, 2);
            if (isset($promo['max_discount']) && $discount > (float) $promo['max_discount']) {
                $discount = (float) $promo['max_discount'];
            }
        } elseif ($promo['type'] === 'fixed') {
            $discount = (float) $promo['value'];
        }

        // Cap discount so total doesn't become negative
        $discount = min($discount, $subtotal);

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => "Kode promo {$promo['code']} berhasil digunakan! Hemat Rp ".number_format($discount, 0, ',', '.'),
            'code' => $promo['code'],
        ];
    }
}
