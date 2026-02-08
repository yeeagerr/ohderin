<?php

use App\Http\Controllers\Kasir\PosController;
use Illuminate\Support\Facades\Route;

// POS Routes
Route::get('/kasir/pos', [PosController::class , 'index'])->name('kasir.pos');
Route::get('/kasir/pos/products', [PosController::class , 'getProducts'])->name('kasir.products');
Route::get('/kasir/pos/categories', [PosController::class , 'getCategories'])->name('kasir.categories');
Route::post('/kasir/pos/checkout', [PosController::class , 'store'])->name('kasir.checkout');

// Orders Route
Route::get('/kasir/orders', function () {
    return view('kasir.orders');
})->name('kasir.order');