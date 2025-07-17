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
    
    // Size management routes
    Route::resource('sizes', \App\Http\Controllers\Admin\SizeController::class);
    
    // Currency management routes
    Route::resource('currencies', \App\Http\Controllers\Admin\CurrencyController::class);
    // We use just 'currencies.set-default' as the name since the admin. prefix gets added automatically by the group
    Route::post('currencies/{currency}/set-default', [\App\Http\Controllers\Admin\CurrencyController::class, 'setDefault'])->name('currencies.set-default');
    
    // Product management routes
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // System settings routes
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Invoice management routes
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    Route::get('invoices/{invoice}/print', [\App\Http\Controllers\Admin\InvoiceController::class, 'printInvoice'])->name('invoices.print');
    Route::post('get-product-info', [\App\Http\Controllers\Admin\InvoiceController::class, 'getProductInfo'])->name('invoices.get-product-info');
    Route::get('get-product-variants/{productId}', [\App\Http\Controllers\Admin\InvoiceController::class, 'getProductVariants'])->name('invoices.get-product-variants');
    
    // Product details management routes (nested)
    Route::get('products/{product}/details', [\App\Http\Controllers\Admin\ProductDetailController::class, 'index'])->name('products.details.index');
    Route::get('products/{product}/details/create', [\App\Http\Controllers\Admin\ProductDetailController::class, 'create'])->name('products.details.create');
    Route::post('products/{product}/details', [\App\Http\Controllers\Admin\ProductDetailController::class, 'store'])->name('products.details.store');
    Route::get('products/{product}/details/json', [\App\Http\Controllers\Admin\ProductDetailController::class, 'getJson'])->name('products.details.json');
    Route::get('products/{product}/details/{detail}/edit', [\App\Http\Controllers\Admin\ProductDetailController::class, 'edit'])->name('products.details.edit');
    Route::put('products/{product}/details/{detail}', [\App\Http\Controllers\Admin\ProductDetailController::class, 'update'])->name('products.details.update');
    Route::delete('products/{product}/details/{detail}', [\App\Http\Controllers\Admin\ProductDetailController::class, 'destroy'])->name('products.details.destroy');
});
