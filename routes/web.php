<?php

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Auth::routes();

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'totalProducts' => Product::count(),
        'totalStock' => Product::sum('quantity'),
        'totalMovements' => StockMovement::count(),
        'lowStock' => Product::where('quantity', '<=', 10)->count(),
        'totalUsers' => User::count(),
        'totalSales' => Sale::sum('total_amount'),
        'stockChartLabels' => Product::orderBy('name')->pluck('name'),
        'stockChartValues' => Product::orderBy('name')->pluck('quantity'),
    ]);
})->middleware('auth')->name('dashboard');

Route::resource('products', ProductController::class)->except(['show']);

Route::get('/products/{product}/stock', [StockController::class, 'create'])->name('stock.create');
Route::post('/products/{product}/stock', [StockController::class, 'store'])->name('stock.store');
Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
