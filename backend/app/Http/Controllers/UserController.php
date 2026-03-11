<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * List all users with order count (admin).
     * GET /api/admin/users
     */
    public function index(): JsonResponse
    {
        $users = User::withCount('orders')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'role'         => $user->role,
                    'orders_count' => $user->orders_count,
                    'created_at'   => $user->created_at,
                ];
            });

        return response()->json($users);
    }
}
