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
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

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

        DB::transaction(function () use ($sale, $newPaymentStatus) {
            if ($newPaymentStatus === 'paid') {
                $sale->update([
                    'payment_status' => 'paid',
                ]);
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
                    ]);
                }
            }
        });

        return response()->json(['status' => 'success', 'payment_status' => $newPaymentStatus]);
    }
}
