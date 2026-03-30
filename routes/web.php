<?php

use App\Http\Controllers\CustomerController;
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
    Route::view('customers', 'pos.customers')->name('pos.customers');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('pos.customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('pos.customers.store');
    Route::get('customers/all', [CustomerController::class, 'index'])->name('pos.customers.index');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('pos.customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('pos.customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('pos.customers.destroy');
    Route::view('inventory', 'pos.page', ['title' => 'Inventory'])->name('pos.inventory');
    Route::view('reports', 'pos.page', ['title' => 'Reports'])->name('pos.reports');
    Route::view('settings', 'pos.page', ['title' => 'Settings'])->name('pos.settings');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
