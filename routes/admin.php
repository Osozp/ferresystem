<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureCommerceIsActive;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', EnsureCommerceIsActive::class])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);

    // Rutas de Control de Caja (Arqueo)
    Route::get('/cash', [CashRegisterController::class, 'index'])->name('cash.index');
    Route::post('/cash', [CashRegisterController::class, 'store'])->name('cash.store');
    Route::put('/cash/{cashRegister}/close', [CashRegisterController::class, 'close'])->name('cash.close');

    Route::resource('users', UserController::class)
        ->middleware('can:isOwner,App\Models\User');
    });
