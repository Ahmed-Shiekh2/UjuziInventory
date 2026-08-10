<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Auth::routes();

Route::get('/home', function () {
    return redirect()->route('products.index');
})->name('home');

Route::resource('products', ProductController::class);

Route::get('/products/{product}/stock', [StockController::class, 'create'])->name('stock.create');
Route::post('/products/{product}/stock', [StockController::class, 'store'])->name('stock.store');