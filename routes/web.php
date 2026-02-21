<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    $guitars = \App\Models\Guitar::where('is_active', true)->latest()->take(6)->get();
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'guitars' => $guitars,
    ]);
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
Route::get('dashboard/export/excel', [App\Http\Controllers\DashboardController::class, 'exportExcel'])->middleware(['auth', 'verified'])->name('dashboard.export.excel');
Route::get('dashboard/export/pdf', [App\Http\Controllers\DashboardController::class, 'exportPdf'])->middleware(['auth', 'verified'])->name('dashboard.export.pdf');

Route::post('orders', [App\Http\Controllers\Api\OrderController::class, 'store'])->middleware(['auth', 'verified'])->name('orders.store');

Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('guitars', function () {
        return Inertia::render('Admin/Guitars');
    })->name('admin.guitars');

    // Kelola Gitar
    Route::get('/data/guitars', [App\Http\Controllers\Api\GuitarController::class, 'index'])->name('admin.guitars.data');
    Route::post('/data/guitars', [App\Http\Controllers\Api\GuitarController::class, 'store']);
    Route::put('/data/guitars/{guitar}', [App\Http\Controllers\Api\GuitarController::class, 'update']);
    Route::delete('/data/guitars/{guitar}', [App\Http\Controllers\Api\GuitarController::class, 'destroy']);

    // Kelola Pesanan
    Route::get('/data/orders', [App\Http\Controllers\Api\OrderController::class, 'index'])->name('admin.orders.data');
    Route::put('/data/orders/{order}/status', [App\Http\Controllers\Api\OrderController::class, 'updateStatus']);

    Route::get('orders', function () {
        return Inertia::render('Admin/Orders');
    })->name('admin.orders');
});

// Google Auth
Route::get('auth/google', [App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

require __DIR__ . '/settings.php';
