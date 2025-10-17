<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:admin');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('role:admin');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:admin');
    
    // Cart
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{productId}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart/get', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/confirm', [CartController::class, 'confirmOrder'])->name('cart.confirm');
    
    // Sales
    Route::get('/sales/data', [SalesController::class, 'index'])->name('sales.data');
    Route::get('/sales/history', [SalesController::class, 'history'])->name('sales.history');
    Route::post('/sales/confirm', [SalesController::class, 'store'])->name('sales.confirm');
    Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('sales.delete');
    Route::get('/sales/export-pdf', [SalesController::class, 'exportPDF'])->name('sales.export.pdf');
});

Route::redirect('/', '/dashboard');