<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Create a Xendit invoice for the given order.
     * POST /api/payments/{orderId}/pay
     */
    public function createInvoice(Request $request, string $orderId): JsonResponse
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Only allow payment for unpaid or failed/expired orders
        if (!in_array($order->payment_status, ['unpaid', 'failed', 'expired'])) {
            return response()->json([
                'message' => 'This order cannot be paid. Current status: ' . $order->payment_status,
            ], 422);
        }

        try {
            $result = $this->xenditService->createInvoice($order, $request->user());

            $order->update([
                'payment_method'     => 'xendit',
                'payment_status'     => 'pending',
                'xendit_invoice_id'  => $result['invoice_id'],
                'xendit_payment_url' => $result['invoice_url'],
            ]);

            return response()->json([
                'message'     => 'Payment invoice created.',
                'payment_url' => $result['invoice_url'],
                'invoice_id'  => $result['invoice_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create payment invoice. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle Xendit payment callback/webhook.
     * POST /api/xendit/callback
     */
    public function handleCallback(Request $request): JsonResponse
    {
        // Verify callback token
        $callbackToken = $request->header('X-CALLBACK-TOKEN', '');
        if (!$this->xenditService->verifyCallbackToken($callbackToken)) {
            Log::warning('Xendit callback: invalid token', [
                'token' => $callbackToken,
            ]);
            return response()->json(['message' => 'Invalid callback token.'], 403);
        }

        $externalId     = $request->input('external_id');
        $status         = $request->input('status');
        $paymentChannel = $request->input('payment_channel');
        $paidAt         = $request->input('paid_at');

        Log::info('Xendit callback received', [
            'external_id'     => $externalId,
            'status'          => $status,
            'payment_channel' => $paymentChannel,
        ]);

        // Extract order ID from external_id (format: "order-{id}-{timestamp}")
        $parts = explode('-', $externalId);
        if (count($parts) < 2) {
            return response()->json(['message' => 'Invalid external_id format.'], 400);
        }
        $orderId = $parts[1];

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Map Xendit status to our payment_status
        $paymentStatus = match (strtoupper($status)) {
            'PAID', 'SETTLED' => 'paid',
            'EXPIRED'         => 'expired',
            'FAILED'          => 'failed',
            default           => 'pending',
        };

        $updateData = [
            'payment_status'  => $paymentStatus,
            'payment_channel' => $paymentChannel,
        ];

        if ($paymentStatus === 'paid') {
            $updateData['paid_at'] = $paidAt ? now()->parse($paidAt) : now();
        }

        $order->update($updateData);

        return response()->json(['message' => 'Callback processed successfully.']);
    }

    /**
     * Check payment status by querying Xendit API directly.
     * GET /api/payments/{orderId}/status
     * Useful in dev/test when callbacks can't reach localhost.
     */
    public function checkStatus(Request $request, string $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!$order->xendit_invoice_id) {
            return response()->json([
                'message'        => 'No payment invoice found.',
                'payment_status' => $order->payment_status ?? 'unpaid',
            ]);
        }

        try {
            $invoiceStatus = $this->xenditService->getInvoiceStatus($order->xendit_invoice_id);

            $paymentStatus = match (strtoupper($invoiceStatus['status'])) {
                'PAID', 'SETTLED' => 'paid',
                'EXPIRED'         => 'expired',
                'FAILED'          => 'failed',
                'PENDING'         => 'pending',
                default           => $order->payment_status,
            };

            $updateData = [
                'payment_status'  => $paymentStatus,
                'payment_channel' => $invoiceStatus['payment_method'] ?? $order->payment_channel,
            ];

            if ($paymentStatus === 'paid' && !$order->paid_at) {
                $updateData['paid_at'] = now();
            }

            $order->update($updateData);

            return response()->json([
                'message'         => 'Payment status checked.',
                'payment_status'  => $paymentStatus,
                'payment_channel' => $updateData['payment_channel'],
                'paid_at'         => $order->fresh()->paid_at,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'        => 'Failed to check payment status.',
                'payment_status' => $order->payment_status,
            ], 500);
        }
    }
}
