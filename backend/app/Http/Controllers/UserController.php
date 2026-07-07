<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            ->map(fn(User $user) => $this->formatUser($user));

        return response()->json($users);
    }

    /**
     * Create a new user account (admin).
     * POST /api/admin/users
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in(['user', 'sub-admin'])],
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'phone'    => $validated['phone'] ?? null,
        ]);

        // Load orders_count
        $user->loadCount('orders');

        return response()->json([
            'message' => 'User account created successfully.',
            'user'    => $this->formatUser($user),
        ], 201);
    }

    /**
     * Show a single user (admin).
     * GET /api/admin/users/{id}
     */
    public function show(string $id): JsonResponse
    {
        $user = User::withCount('orders')->findOrFail($id);

        return response()->json($this->formatUser($user));
    }

    /**
     * Update a user account (admin).
     * PUT /api/admin/users/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent changing own role or editing the super-admin
        if ($user->role === 'admin' && $request->user()->id !== $user->id) {
            return response()->json([
                'message' => 'Cannot modify another admin account.',
            ], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => ['required', Rule::in(['user', 'sub-admin'])],
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->loadCount('orders');

        return response()->json([
            'message' => 'User updated successfully.',
            'user'    => $this->formatUser($user->fresh()->loadCount('orders')),
        ]);
    }

    /**
     * Delete a user account (admin).
     * DELETE /api/admin/users/{id}
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account or another admin
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Cannot delete an admin account.',
            ], 403);
        }

        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Cannot delete your own account.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }

    /**
     * Format user data for response.
     */
    private function formatUser(User $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'phone'        => $user->phone,
            'role'         => $user->role,
            'orders_count' => $user->orders_count ?? 0,
            'created_at'   => $user->created_at,
        ];
    }
}
