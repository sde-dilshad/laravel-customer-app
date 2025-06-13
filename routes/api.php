<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CustomerController;

Route::name('api.')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum')->name('user');

    Route::get('/mfa/initiate', [AuthController::class, 'initiateMfa'])->name('mfa.initiate');
    Route::post('/mfa/verify', [AuthController::class, 'verifyMfa'])->name('mfa.verify');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::name('customers.')->group(function () {
            Route::get('/customers', [CustomerController::class, 'index'])->name('index');
            Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::post('/customers', [CustomerController::class, 'store'])->name('store');
            Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

});
