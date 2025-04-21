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
    
    // Citizen routes - keep as is for now
    Route::middleware(['role:citizen'])->group(function () {
        Route::get('/bookings/create/{service}', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings/{service}', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/my-bookings', [BookingController::class, 'index'])->name('citizen.bookings');
        Route::post('/reviews/{booking}', [ReviewController::class, 'store'])->name('reviews.store');
    });
    
    // LSP routes - change to individual routes temporarily
    // Comment out the group middleware
    // Route::middleware(['role:lsp'])->group(function () {
        
    // Add auth middleware to each route individually for testing
    Route::get('/lsp/dashboard', function () {
        return view('lsp.dashboard');
    })->middleware(['auth'])->name('lsp.dashboard');
    
    // Profile management - with explicit middleware
    Route::get('/lsp/profile/create', [LspProfileController::class, 'create'])
        ->middleware(['auth'])
        ->name('lsp.profile.create');
        
    Route::post('/lsp/profile', [LspProfileController::class, 'store'])
        ->middleware(['auth'])
        ->name('lsp.profile.store');
    
    Route::get('/lsp/profile/edit', [LspProfileController::class, 'edit'])
        ->middleware(['auth'])
        ->name('lsp.profile.edit');
    
    Route::put('/lsp/profile', [LspProfileController::class, 'update'])
        ->middleware(['auth'])
        ->name('lsp.profile.update');
    
    // Rest of LSP routes with auth middleware
    Route::get('/lsp/services', [ServiceController::class, 'index'])
        ->middleware(['auth'])
        ->name('lsp.services.index');
        
    Route::get('/lsp/services/create', [ServiceController::class, 'create'])
        ->middleware(['auth'])
        ->name('lsp.services.create');
        
    Route::post('/lsp/services', [ServiceController::class, 'store'])
        ->middleware(['auth'])
        ->name('lsp.services.store');
        
    Route::get('/lsp/services/{service}/edit', [ServiceController::class, 'edit'])
        ->middleware(['auth'])
        ->name('lsp.services.edit');
        
    Route::put('/lsp/services/{service}', [ServiceController::class, 'update'])
        ->middleware(['auth'])
        ->name('lsp.services.update');
        
    Route::delete('/lsp/services/{service}', [ServiceController::class, 'destroy'])
        ->middleware(['auth'])
        ->name('lsp.services.destroy');
        
    // Booking management
    Route::get('/lsp/bookings', [BookingController::class, 'index'])
        ->middleware(['auth'])
        ->name('lsp.bookings');
        
    Route::put('/lsp/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
        ->middleware(['auth'])
        ->name('lsp.bookings.status');
        
    Route::post('/lsp/reviews/{review}/respond', [ReviewController::class, 'respond'])
        ->middleware(['auth'])
        ->name('lsp.reviews.respond');
    // }); // End of LSP group comment
    
    // Shared routes
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/verifications', [AdminController::class, 'pendingVerifications'])->name('admin.verifications');
        Route::post('/admin/verify/{profile}', [AdminController::class, 'verifyLsp'])->name('admin.verify');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
        Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    });
});