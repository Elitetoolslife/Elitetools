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
Route::get('signup', [SignupController::class, 'showSignupPage'])->name('signup');
Route::post('signup', [SignupController::class, 'signup'])->name('signup.submit');
Route::get('login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Authentication
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| Normal User Routes
|--------------------------------------------------------------------------
| Routes for logged-in users with 'auth' middleware.
*/

Route::middleware(['auth', 'auth-session:user'])->group(function () {
    Route::get('/home', [UserHomeController::class, 'index'])->name('home');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('profile', [UserController::class, 'profile'])->name('profile');
        Route::get('account', [UserController::class, 'account'])->name('account');
        Route::get('security', [UserController::class, 'security'])->name('security');
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
    });

    // Profile Routes
    Route::get('/user/profile-settings', [ProfileController::class, 'index'])->name('user.profile-settings.index');

    // Orders Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('history', [OrderController::class, 'history'])->name('history');
        Route::get('pending', [OrderController::class, 'pending'])->name('pending');
        Route::get('track', [OrderController::class, 'track'])->name('track');
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });

    // Balance Management
    Route::post('/balance/add', [BalanceController::class, 'add'])->name('balance.add');

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
    Route::resource('buyer/bank', BankController::class)->only(['index'])->names(['index' => 'buyers.bank']);

    // SSH Purchase
    Route::post('buy-ssh/{id}', [SSHController::class, 'buySsh'])->name('buySsh');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Routes for admin panel functionality.
*/

Route::prefix('admin')->name('admin.')->middleware('auth-session:admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');                // List users
    Route::match(['get', 'post'], 'users/search', [UserController::class, 'search'])->name('users.search');  // Search users
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');        // Edit user
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');         // Update user
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');    // Delete user
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');             // Show user details
    Route::get('users/{id}/make-reseller', [UserController::class, 'make'])->name('users.makeReseller'); // Make user a reseller
});

/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
| Routes for seller panel functionality.
*/

Route::middleware(['auth', 'auth-session:seller'])->group(function () {
    Route::get('/seller/home', [SellerController::class, 'index'])->name('seller.home');
});

/*
|--------------------------------------------------------------------------
| Navigation Route
|--------------------------------------------------------------------------
| General navigation route for fetching navigation elements dynamically.
*/

Route::get('/navbar', [NavigationController::class, 'index'])->name('navbar');
Route::get('/', [NavigationController::class, 'showNavbar'])->name('home');

/*
|--------------------------------------------------------------------------
| Fallback Routes
|--------------------------------------------------------------------------
| Miscellaneous or placeholder routes.
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});