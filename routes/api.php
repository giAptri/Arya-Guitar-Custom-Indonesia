<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\GuitarController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CatalogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/catalog/{slug}', [CatalogController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated User Routes (Customer)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']); // User can see their orders
    Route::get('/orders/{order}', [OrderController::class, 'show']); // Customer track specific order
});

// Admin View (Guitar List for Management) - Optional: Move to Admin group if strict
// Route::get('/guitars', [GuitarController::class, 'index']);
// Route::get('/guitars/{guitar}', [GuitarController::class, 'show']);

// Protected Routes (Admin/Staff)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']); // Admin Only

    // Guitar Management (Create, Update, Delete)
    // Admin also needs to list/show guitars to manage them, usually sharing the controller or using specific ones
    // For now we can keep GuitarController read methods public OR move here.
    // Given the requirement "Customer hanya bisa akses landing page (Catalog)", implies GuitarController might be Admin internal.
    Route::get('/guitars', [GuitarController::class, 'index']);
    Route::get('/guitars/{guitar}', [GuitarController::class, 'show']);

    Route::post('/guitars', [GuitarController::class, 'store']);
    Route::match(['put', 'patch'], '/guitars/{guitar}', [GuitarController::class, 'update']);
    Route::delete('/guitars/{guitar}', [GuitarController::class, 'destroy']);

    // Order Management
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
});

