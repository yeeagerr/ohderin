<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/dashboard/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/dashboard/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/dashboard/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/dashboard/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

Route::get('/dashboard/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/dashboard/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/dashboard/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');


// Route::resource('/dashboard/products', ProductController::class)->except(['show', 'create', 'edit']);
Route::patch('/dashboard/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');