<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect the home page to login for now
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Sales Dashboard Route (protected)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User management routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Category management routes
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Brand management routes
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    
    // Customer management routes
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    
    // Supplier management routes
    Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class);
});
