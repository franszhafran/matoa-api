<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth0Controller;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'list'])->name('list');
        Route::get('/{id}', [ProductController::class, 'detail'])->name('detail');
        Route::post('/', [ProductController::class, 'store'])->name('store');
    });

    Route::get('/transactions', [TransactionController::class, 'listAdmin'])->name('listAdmin');
    Route::post('/transactions', [TransactionController::class, 'confirm'])->name('confirm');
});

Route::prefix('profile')->name('profile.')->middleware('auth0')->group(function () {
    Route::get('/', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/', [CustomerController::class, 'update'])->name('updateProfile');
    
    Route::get('/cart', [CartController::class, 'detail'])->name('detail');
    Route::post('/cart', [CartController::class, 'addToCart'])->name('addToCart');
    Route::post('/cart-set', [CartController::class, 'setCart'])->name('setCart');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    
});

Route::prefix('customer')->name('customer.')->middleware('auth0')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'list'])->name('list');
    Route::get('/transactions/{id}', [TransactionController::class, 'detail'])->name('detail');
});

Route::prefix('auth0')->name('auth0.')->group(function () {
    Route::get('callback', [Auth0Controller::class, 'callback'])->name('callback');
    Route::get('login', [Auth0Controller::class, 'login'])->name('login');
    Route::get('logout', [Auth0Controller::class, 'logout'])->name('logout');
    Route::get('info', [Auth0Controller::class, 'info'])->name('info')->middleware('auth0');;
});