<?php

use App\Http\Controllers\Kasir\OrderController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\RegisterController;
use Illuminate\Support\Facades\Route;

// Register Management
Route::get('/kasir/registers', [RegisterController::class, 'index'])->name('kasir.registers.index');
Route::post('/kasir/registers', [RegisterController::class, 'store'])->name('kasir.registers.store');
Route::put('/kasir/registers/{register}', [RegisterController::class, 'update'])->name('kasir.registers.update');
Route::delete('/kasir/registers/{register}', [RegisterController::class, 'destroy'])->name('kasir.registers.delete');
Route::post('/kasir/registers/{register}/enter', [RegisterController::class, 'enter'])->name('kasir.registers.enter');
Route::post('/kasir/registers/{register}/open', [RegisterController::class, 'open'])->name('kasir.registers.open');
Route::get('/kasir/register-sessions/history', [RegisterController::class, 'history'])->name('kasir.registers.history');
Route::get('/kasir/register-sessions/{registerSession}/summary', [RegisterController::class, 'summary'])->name('kasir.registers.summary');
Route::post('/kasir/register-sessions/{registerSession}/close', [RegisterController::class, 'close'])->name('kasir.registers.close');
Route::get('/kasir/register-status', [RegisterController::class, 'status'])->name('kasir.registers.status');

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
Route::post('/kasir/orders/{id}/refund', [OrderController::class, 'refund'])->name('kasir.orders.refund');

// Kasir Stock Opnames
Route::get('/kasir/stock-opnames', [\App\Http\Controllers\Kasir\StockOpnameController::class, 'index'])->name('kasir.stock-opnames.index');
Route::post('/kasir/stock-opnames', [\App\Http\Controllers\Kasir\StockOpnameController::class, 'store'])->name('kasir.stock-opnames.store');
