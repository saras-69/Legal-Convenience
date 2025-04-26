<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LspProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/services/search', [ServiceController::class, 'search'])->name('services.search');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Authentication routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('verify.otp');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Shared routes that need authentication
    Route::get('/bookings/create/{service}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store/{service}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    
    // Citizen routes
    Route::middleware(['role:citizen'])->group(function () {
        Route::get('/my-bookings', [BookingController::class, 'index'])->name('citizen.bookings');
        Route::post('/reviews/{booking}', [ReviewController::class, 'store'])->name('reviews.store');
    });
    
    // LSP routes
    Route::middleware(['role:lsp'])->group(function () {
        Route::get('/lsp/dashboard', function () {
            return view('lsp.dashboard');
        })->name('lsp.dashboard');
        
        // Profile management
        Route::get('/lsp/profile/create', [LspProfileController::class, 'create'])->name('lsp.profile.create');
        Route::post('/lsp/profile', [LspProfileController::class, 'store'])->name('lsp.profile.store');
        Route::get('/lsp/profile/edit', [LspProfileController::class, 'edit'])->name('lsp.profile.edit');
        Route::put('/lsp/profile', [LspProfileController::class, 'update'])->name('lsp.profile.update');
        
        // Service management
        Route::get('/lsp/services', [ServiceController::class, 'index'])->name('lsp.services.index');
        Route::get('/lsp/services/create', [ServiceController::class, 'create'])->name('lsp.services.create');
        Route::post('/lsp/services', [ServiceController::class, 'store'])->name('lsp.services.store');
        Route::get('/lsp/services/{service}/edit', [ServiceController::class, 'edit'])->name('lsp.services.edit');
        Route::put('/lsp/services/{service}', [ServiceController::class, 'update'])->name('lsp.services.update');
        Route::delete('/lsp/services/{service}', [ServiceController::class, 'destroy'])->name('lsp.services.destroy');
        
        // Booking management
        Route::get('/lsp/bookings', [BookingController::class, 'index'])->name('lsp.bookings');
        Route::put('/lsp/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('lsp.bookings.status');
        Route::post('/lsp/reviews/{review}/respond', [ReviewController::class, 'respond'])->name('lsp.reviews.respond');
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/verifications', [AdminController::class, 'pendingVerifications'])->name('admin.verifications');
        Route::post('/admin/verify/{profile}', [AdminController::class, 'verifyLsp'])->name('admin.verify');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
        Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.bookings');
    });
});
