<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('customers', function () {
        return Inertia::render('Customers');
    })->name('customers.index');
    
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::get('customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
