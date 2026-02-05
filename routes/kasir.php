<?php

use Illuminate\Support\Facades\Route;

Route::get('/kasir/pos', function () {
    return view('kasir.pos');
})->name('kasir.pos');

Route::get('/kasir/orders', function () {
    return view('kasir.orders');
})->name('kasir.order');