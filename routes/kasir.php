<?php

use App\Http\Controllers\Kasir\OrderController;
use App\Http\Controllers\Kasir\PosController;
use Illuminate\Support\Facades\Route;

// POS Routes
Route::get('/kasir/pos', [PosController::class, 'index'])->name('kasir.pos');
Route::get('/kasir/pos/products', [PosController::class, 'getProducts'])->name('kasir.products');
Route::get('/kasir/pos/categories', [PosController::class, 'getCategories'])->name('kasir.categories');
Route::post('/kasir/pos/checkout', [PosController::class, 'store'])->name('kasir.checkout');

// Draft/Hold
Route::post('/kasir/pos/hold', [PosController::class, 'holdOrder'])->name('kasir.hold');
Route::get('/kasir/pos/drafts', [PosController::class, 'getDrafts'])->name('kasir.drafts');
Route::get('/kasir/pos/drafts/{id}', [PosController::class, 'resumeDraft'])->name('kasir.drafts.resume');
Route::delete('/kasir/pos/drafts/{id}', [PosController::class, 'deleteDraft'])->name('kasir.drafts.delete');

// Orders 
Route::get('/kasir/orders', [OrderController::class, 'index'])->name('kasir.order');
Route::get('/kasir/orders/data', [OrderController::class, 'getOrders'])->name('kasir.orders.data');
Route::get('/kasir/orders/{id}', [OrderController::class, 'getOrderDetail'])->name('kasir.orders.detail');