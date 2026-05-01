<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModifierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/dashboard/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/dashboard/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/dashboard/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/dashboard/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

Route::get('/dashboard/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/dashboard/products/search', [ProductController::class, 'searchProducts'])->name('products.search');
Route::get('/dashboard/products/{product}/data', [ProductController::class, 'getProduct'])->name('products.data');
Route::post('/dashboard/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/dashboard/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
Route::resource('/dashboard/modifiers', ModifierController::class)->except(['show', 'create', 'edit']);

// Route::resource('/dashboard/products', ProductController::class)->except(['show', 'create', 'edit']);
Route::patch('/dashboard/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
Route::resource('/dashboard/raw-materials', RawMaterialController::class)->except(['show', 'create', 'edit']);

Route::resource('/dashboard/purchases', PurchaseController::class)->except(['show', 'create', 'edit']);

Route::resource('/dashboard/recipes', RecipeController::class);

// Stock Opname
Route::prefix('dashboard')->group(function () {
    Route::resource('stock-opnames', StockOpnameController::class);
    Route::get('stock-summary', [StockOpnameController::class, 'getStockSummary'])->name('stock.summary');
    Route::post('stock-opnames/{stockOpname}/approve', [StockOpnameController::class, 'approve'])->name('stock-opnames.approve');
    Route::post('stock-opnames/{stockOpname}/reject', [StockOpnameController::class, 'reject'])->name('stock-opnames.reject');
    Route::get('stock-opnames/{stockOpname}/print', [StockOpnameController::class, 'print'])->name('stock-opnames.print');
});

// Sales Reports
Route::prefix('dashboard/reports')->group(function () {
    Route::get('sales', [SalesReportController::class, 'index'])->name('sales.index');
    Route::get('transactions', [SalesReportController::class, 'transactions'])->name('transactions.index');
    Route::get('daily-summary', [SalesReportController::class, 'dailySummary'])->name('daily-summary.index');
    Route::get('export', [SalesReportController::class, 'export'])->name('sales.export');
});