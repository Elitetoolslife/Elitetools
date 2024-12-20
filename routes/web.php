<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\HomeController as UserHomeController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SSHController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\CheckSeller;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible without authentication.
*/

Route::view('/', 'welcome')->name('home');  // Homepage
Route::view('index', 'index');              // Index/Overview page for public users

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Routes for user and admin authentication.
*/

// User Authentication
Route::prefix('auth')->group(function () {
    Route::get('signup', [SignupController::class, 'showSignupPage'])->name('auth.signup');
    Route::post('signup', [SignupController::class, 'signup'])->name('auth.signup.submit');
    Route::get('login', [LoginController::class, 'showLoginPage'])->name('auth.login');
    Route::post('login', [LoginController::class, 'login'])->name('auth.login.submit');
    Route::match(['get', 'post'], 'logout', [LoginController::class, 'logout'])->name('auth.logout');
});

// Admin Authentication
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Normal User Routes
|--------------------------------------------------------------------------
| Routes for logged-in users with 'auth' middleware.
*/

Route::middleware(['auth', 'auth-session:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('home', [UserHomeController::class, 'index'])->name('home');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('profile', [UserController::class, 'profile'])->name('profile');
        Route::get('account', [UserController::class, 'account'])->name('account');
        Route::get('security', [UserController::class, 'security'])->name('security');
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
    });

    // Profile Routes
    Route::get('profile-settings', [ProfileController::class, 'index'])->name('profile-settings.index');

    // Orders Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('history', [OrderController::class, 'history'])->name('history');
        Route::get('pending', [OrderController::class, 'pending'])->name('pending');
        Route::get('track', [OrderController::class, 'track'])->name('track');
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });

    // Balance Management
    Route::post('balance/add', [BalanceController::class, 'add'])->name('balance.add');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('create', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::post('{id}/reply', [ReportController::class, 'reply'])->name('reply');
    });

    // Tickets Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::post('{id}/reply', [TicketController::class, 'reply'])->name('reply');
    });

    // Buyer Bank Route
    Route::prefix('buyer')->name('buyer.')->group(function () {
        Route::get('bank', [BankController::class, 'index'])->name('bank');
    });

    // SSH Purchase
    Route::post('buy-ssh/{id}', [SSHController::class, 'buySsh'])->name('buySsh');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Routes for admin panel functionality.
*/

Route::prefix('admin')->middleware(['auth-session:admin'])->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'search', [UserController::class, 'search'])->name('search');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('{id}', [UserController::class, 'update'])->name('update');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('{id}', [UserController::class, 'show'])->name('show');
        Route::get('{id}/make-reseller', [UserController::class, 'make'])->name('makeReseller');
    });
});

/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
| Routes for seller panel functionality.
*/

Route::middleware(['auth', 'auth-session:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('home', [SellerController::class, 'index'])->name('home');
});

/*
|--------------------------------------------------------------------------
| Navigation Route
|--------------------------------------------------------------------------
| General navigation route for fetching navigation elements dynamically.
*/

Route::prefix('navigation')->name('navigation.')->group(function () {
    Route::get('navbar', [NavigationController::class, 'index'])->name('navbar');
    Route::get('/', [NavigationController::class, 'showNavbar'])->name('home');
});

/*
|--------------------------------------------------------------------------
| Fallback Routes
|--------------------------------------------------------------------------
| Miscellaneous or placeholder routes.
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});