<?php

use Illuminate\Support\Facades\Route;




Route::get('signup', [App\Http\Controllers\Auth\SignupController::class, 'showSignupPage'])->name('signup');            
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginPage'])->name('login');
   



Route::post('signup', [App\Http\Controllers\Auth\SignupController::class, 'signup.submit']);
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login.submit']);


Route::post('signup', [App\Http\Controllers\Auth\SignupController::class, 'signup']);
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);






   
Route::get('/', function () {
    return view('welcome');
});

Route::get('index', function () {
       return view('index');
   });