<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;

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

// Admin Authentication
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Routes for admin panel functionality. These routes are typically protected
| by middleware to ensure only authenticated admins can access them.
*/

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
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
| Fallback Routes
|--------------------------------------------------------------------------
| Miscellaneous or placeholder routes.
*/

// Placeholder for users index page if needed
Route::get('users', function () {
    return view('admin.users.index');
})->name('users.index');