<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('orders', [App\Http\Controllers\Api\OrderController::class, 'store'])->middleware(['auth', 'verified'])->name('orders.store');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('guitars', function () {
        return Inertia::render('Admin/Guitars');
    })->name('admin.guitars');
    
    Route::get('orders', function () {
        return Inertia::render('Admin/Orders');
    })->name('admin.orders');
});

require __DIR__.'/settings.php';
