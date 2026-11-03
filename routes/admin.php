<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureCommerceIsActive;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\CommerceController as SuperAdminCommerceController;
use App\Http\Middleware\EnsureIsOwner;
use App\Http\Middleware\EnsureIsSuperAdmin;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', EnsureCommerceIsActive::class])->group(function () {
    Route::get('/', function () {
        if (Auth::user()->role === 'collaborator') {
            return redirect()->route('admin.sales.index');
        }
        return view('dashboard');
    })->name('dashboard');

    // -------------------------------------------------------------
    // RUTAS LIBRES PARA COLABORADORES Y DUEÑOS (Ventas y Caja)
    // -------------------------------------------------------------
    Route::middleware(['cash.open'])->group(function () {
        Route::resource('sales', SaleController::class);
    });

    Route::get('/cash', [CashRegisterController::class, 'index'])->name('cash.index');
    Route::post('/cash', [CashRegisterController::class, 'store'])->name('cash.store');
    Route::put('/cash/{cashRegister}/close', [CashRegisterController::class, 'close'])->name('cash.close');

    // -------------------------------------------------------------
    // RUTAS EXCLUSIVAS PARA EL DUEÑO (Bloqueadas para 'collaborator')
    // -------------------------------------------------------------
    Route::middleware([EnsureIsOwner::class])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);

        Route::patch('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');

        Route::middleware(['cash.open'])->group(function () {
            Route::resource('purchases', PurchaseController::class);
        });
    });

    // Rutas de Super Admin
    Route::prefix('superadmin')
        ->name('superadmin.')
        ->middleware(['auth', EnsureIsSuperAdmin::class])
        ->group(function () {

            // Gestión de Comercios
            Route::get('/commerces', [SuperAdminCommerceController::class, 'index'])->name('commerces.index');
            Route::post('/commerces', [SuperAdminCommerceController::class, 'store'])->name('commerces.store');
            Route::patch('/commerces/{commerce}/status', [SuperAdminCommerceController::class, 'updateStatus'])->name('commerces.updateStatus');
            Route::post('/commerces/{commerce}/extend', [SuperAdminCommerceController::class, 'extendSubscription'])->name('commerces.extend');

            Route::get('/commerces/{commerce}/impersonate', [SuperAdminCommerceController::class, 'impersonate'])->name('commerces.impersonate');
            Route::get('/impersonate/leave', [SuperAdminCommerceController::class, 'leaveImpersonation'])->name('commerces.leave');
            // Ruta para salir de la suplantación y volver al panel de super admin
        });
});
