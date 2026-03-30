<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('sales', 'pos.page', ['title' => 'Sales / Billing'])->name('pos.sales');
    Route::view('products', 'pos.page', ['title' => 'Products'])->name('pos.products');
    Route::view('customers', 'pos.page', ['title' => 'Customers'])->name('pos.customers');
    Route::view('inventory', 'pos.page', ['title' => 'Inventory'])->name('pos.inventory');
    Route::view('reports', 'pos.page', ['title' => 'Reports'])->name('pos.reports');
    Route::view('settings', 'pos.page', ['title' => 'Settings'])->name('pos.settings');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
