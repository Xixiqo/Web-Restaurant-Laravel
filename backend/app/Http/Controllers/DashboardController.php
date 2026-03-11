<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard statistics.
     * GET /api/admin/dashboard
     */
    public function index(): JsonResponse
    {
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'dipesan')->count();
        $deliveringOrders = Order::where('status', 'diantar')->count();
        $completedOrders = Order::where('status', 'selesai')->count();
        $totalRevenue    = Order::where('status', 'selesai')->sum('total_price');
        $totalUsers      = User::where('role', 'user')->count();
        $totalMenuItems  = Product::count();

        // Recent orders (last 5)
        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id'          => $order->id,
                    'user'        => $order->user ? [
                        'name'  => $order->user->name,
                        'email' => $order->user->email,
                    ] : null,
                    'total_price' => (float) $order->total_price,
                    'status'      => $order->status,
                    'items_count' => $order->items->count(),
                    'created_at'  => $order->created_at,
                ];
            });

        return response()->json([
            'total_orders'      => $totalOrders,
            'pending_orders'    => $pendingOrders,
            'delivering_orders' => $deliveringOrders,
            'completed_orders'  => $completedOrders,
            'total_revenue'     => (float) $totalRevenue,
            'total_users'       => $totalUsers,
            'total_menu_items'  => $totalMenuItems,
            'recent_orders'     => $recentOrders,
        ]);
    }
}
