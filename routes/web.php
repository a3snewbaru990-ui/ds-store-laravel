<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/pricelist', '/#pricelist')->name('pricelist');

Route::get('/lacak', [OrderTrackingController::class, 'index'])->name('track.index');
Route::post('/lacak', [OrderTrackingController::class, 'track'])
    ->middleware('throttle:10,1')
    ->name('track');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/order/{package}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders/{order}', [DashboardController::class, 'show'])->name('orders.show');
    Route::get('/dashboard/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/dashboard/profil', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
        Route::resource('packages', AdminPackageController::class)->except(['show']);
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    });

