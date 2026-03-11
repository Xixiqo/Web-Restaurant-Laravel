<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    /**
     * Create a Xendit invoice for the given order.
     *
     * @return array{invoice_id: string, invoice_url: string}
     */
    public function createInvoice(Order $order, User $user): array
    {
        $externalId = 'order-' . $order->id . '-' . time();

        $items = $order->items->map(function ($item) {
            return [
                'name'     => $item->product?->name ?? 'Product',
                'quantity' => $item->quantity,
                'price'    => (float) $item->price,
            ];
        })->toArray();

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id'          => $externalId,
            'amount'               => (float) $order->total_price,
            'payer_email'          => $user->email,
            'description'          => 'Sunuy House - Order #' . $order->id,
            'currency'             => 'IDR',
            'items'                => $items,
            'success_redirect_url' => $frontendUrl . '/payment/' . $order->id . '?status=success',
            'failure_redirect_url' => $frontendUrl . '/payment/' . $order->id . '?status=failed',
        ]);

        try {
            $invoice = $this->invoiceApi->createInvoice($createInvoiceRequest);

            return [
                'invoice_id'  => $invoice->getId(),
                'invoice_url' => $invoice->getInvoiceUrl(),
            ];
        } catch (\Exception $e) {
            Log::error('Xendit invoice creation failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify the callback token from Xendit webhook.
     */
    public function verifyCallbackToken(string $token): bool
    {
        return $token === config('services.xendit.callback_token');
    }

    /**
     * Get the status of a Xendit invoice by its ID.
     *
     * @return array{status: string, payment_method: string|null}
     */
    public function getInvoiceStatus(string $invoiceId): array
    {
        try {
            $invoice = $this->invoiceApi->getInvoiceById($invoiceId);

            return [
                'status'         => $invoice->getStatus(),
                'payment_method' => $invoice->getPaymentMethod(),
            ];
        } catch (\Exception $e) {
            Log::error('Xendit get invoice failed', [
                'invoice_id' => $invoiceId,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
