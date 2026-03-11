<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ─── Public Auth Routes ─────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// ─── Public Product Routes ──────────────────────────────────────────
Route::get('/products',      [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// ─── Authenticated User Routes ──────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Orders (user)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders',  [OrderController::class, 'userOrders']);

    // Reservations (user)
    Route::post('/reservations', [ReservationController::class, 'store']);
});

// ─── Admin Routes ───────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Product management (create, update, delete)
    Route::post('/products',          [ProductController::class, 'store']);
    Route::put('/products/{id}',      [ProductController::class, 'update']);
    Route::post('/products/{id}',     [ProductController::class, 'update']); // for multipart _method=PUT
    Route::delete('/products/{id}',   [ProductController::class, 'destroy']);

    // Order management
    Route::get('/orders',                  [OrderController::class, 'index']);
    Route::patch('/orders/{id}/status',    [OrderController::class, 'updateStatus']);

    // User management
    Route::get('/users', [UserController::class, 'index']);
});
