<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Models\Product;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\AdminMiddleware;




Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', AdminMiddleware::class])
    ->name('admin.') // ✅ add prefix for route names
    ->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products');         // admin.products
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create'); // admin.products.create
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');   // admin.products.store
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/{product}', [ProductController::class, 'update'])->name('products.update');       
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

/*
Route::prefix('admin')
    ->middleware(['auth', AdminMiddleware::class])  

    ->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    });


/*
Route::middleware(['auth', 'admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function() {
         Route::get('/products', [ProductController::class, 'index'])->name('products');
         Route::post('/products', [ProductController::class, 'store'])->name('products.store');
     });*/

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
     ->name('logout');

require __DIR__.'/auth.php';
