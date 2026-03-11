<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Place a new order (authenticated user).
     * POST /api/orders
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'notes'              => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $totalPrice = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemPrice   = $product->price * $item['quantity'];
                $totalPrice += $itemPrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                ];
            }

            $order = Order::create([
                'user_id'     => $request->user()->id,
                'total_price' => $totalPrice,
                'status'      => 'dipesan',
                'notes'       => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            $order->load('items.product');

            return response()->json([
                'message' => 'Order placed successfully.',
                'order'   => $this->formatOrder($order),
            ], 201);
        });
    }

    /**
     * Get authenticated user's orders.
     * GET /api/orders
     */
    public function userOrders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => $this->formatOrder($order));

        return response()->json($orders);
    }

    /**
     * Get all orders (admin).
     * GET /api/admin/orders
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['dipesan', 'diantar', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->get()->map(fn($order) => $this->formatOrder($order, true));

        return response()->json($orders);
    }

    /**
     * Update order status (admin).
     * PATCH /api/admin/orders/{id}/status
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:dipesan,diantar,selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);
        $order->load(['user', 'items.product']);

        return response()->json([
            'message' => 'Order status updated.',
            'order'   => $this->formatOrder($order, true),
        ]);
    }

    /**
     * Format order data for response.
     */
    private function formatOrder(Order $order, bool $includeUser = false): array
    {
        $data = [
            'id'                 => $order->id,
            'total_price'        => (float) $order->total_price,
            'status'             => $order->status,
            'notes'              => $order->notes,
            'payment_status'     => $order->payment_status ?? 'unpaid',
            'payment_method'     => $order->payment_method,
            'payment_channel'    => $order->payment_channel,
            'xendit_payment_url' => $order->xendit_payment_url,
            'paid_at'            => $order->paid_at,
            'items'              => $order->items->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => (float) $item->price,
                    'product'    => $item->product ? [
                        'id'    => $item->product->id,
                        'name'  => $item->product->name,
                        'image' => $item->product->image_url,
                    ] : null,
                ];
            }),
            'created_at'         => $order->created_at,
            'updated_at'         => $order->updated_at,
        ];

        if ($includeUser && $order->user) {
            $data['user'] = [
                'id'    => $order->user->id,
                'name'  => $order->user->name,
                'email' => $order->user->email,
            ];
        }

        return $data;
    }
}
