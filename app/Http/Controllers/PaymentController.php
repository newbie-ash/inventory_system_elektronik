<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Handle incoming Midtrans HTTP Webhook Notification.
     */
    public function notification(Request $request): JsonResponse
    {
        $payload = $request->json()->all() ?: $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        if (! $orderId || ! $signatureKey) {
            return response()->json(['message' => 'Invalid notification payload.'], 400);
        }

        // Verify authenticity of signature
        if (! $this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans webhook signature mismatch', ['order_id' => $orderId]);

            return response()->json(['message' => 'Invalid signature key.'], 403);
        }

        $sale = Sale::with('items')->where('sale_number', $orderId)->first();

        if (! $sale) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $parsed = $this->midtransService->parseNotificationStatus($transactionStatus, $fraudStatus);
        $newPaymentStatus = $parsed['payment_status'];

        DB::transaction(function () use ($sale, $newPaymentStatus, $paymentType, $transactionStatus) {
            if ($newPaymentStatus === 'paid') {
                $updateData = [
                    'payment_status' => 'paid',
                    'shipping_status' => ($sale->shipping_status === 'pending' || empty($sale->shipping_status)) ? 'packing' : $sale->shipping_status,
                ];

                if (! empty($paymentType)) {
                    $updateData['payment_method'] = 'midtrans ('.$paymentType.')';
                }

                $sale->update($updateData);

                Log::info("Midtrans webhook: Order #{$sale->sale_number} marked as PAID via {$paymentType} ({$transactionStatus})");
            } elseif ($newPaymentStatus === 'failed') {
                // Restore stock automatically if payment failed or expired
                if ($sale->status !== 'cancelled') {
                    foreach ($sale->items as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);

                            StockMovement::create([
                                'product_id' => $product->id,
                                'user_id' => $sale->user_id,
                                'type' => 'in',
                                'quantity' => $item->quantity,
                                'notes' => "Reversal Otomatis (Pembayaran Batal/Expired) #{$sale->sale_number}",
                            ]);
                        }
                    }

                    $sale->update([
                        'status' => 'cancelled',
                        'payment_status' => 'failed',
                        'shipping_status' => 'cancelled',
                    ]);

                    Log::info("Midtrans webhook: Order #{$sale->sale_number} CANCELLED and stock restored.");
                }
            }
        });

        $sale->refresh();

        return response()->json([
            'status' => 'success',
            'order_id' => $sale->sale_number,
            'payment_status' => $sale->payment_status,
            'shipping_status' => $sale->shipping_status,
            'transaction_status' => $transactionStatus,
        ]);
    }
}
