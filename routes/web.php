<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminStoreController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\SuperAdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // GOOGLE OAUTH (ONLY ONE FLOW)
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});

// 1. Public Front-End
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/store/{id}', [StoreController::class, 'show'])->name('store.detail');
Route::view('/tentang', 'about')->name('about');
Route::get('/kontak', [ContactController::class, 'show'])->name('contact');
Route::post('/kontak', [ContactController::class, 'submit'])->name('contact.submit');

// 2. Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register-toko', [AuthController::class, 'showRegisterToko'])->name('register-toko');
Route::post('/register-toko', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth & OTP Authentication Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/register/otp/verify', [AuthController::class, 'showOtpRegisterVerify'])->name('register.otp.verify');
Route::post('/register/otp/verify', [AuthController::class, 'verifyOtpRegister']);
Route::post('/register/otp/resend', [AuthController::class, 'resendOtpRegister'])->name('register.otp.resend');


// 3. User Actions (Authenticated, Role: User)
Route::post('/store/{id}/review', [ReviewController::class, 'store'])
    ->middleware(['auth', 'role:user'])
    ->name('review.store');

// 4. Store Registration Area (No ensure_store middleware to prevent loops)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/register-store', [AdminStoreController::class, 'showRegistrationForm'])->name('admin.register-store');
        Route::post('/register-store', [AdminStoreController::class, 'registerStore'])->name('admin.register-store.submit');
    });

// 5. Admin & Super Admin Area
Route::middleware(['auth', 'role:admin,super_admin', 'ensure_store'])
    ->prefix('admin')
    ->group(function () {
        
        // Dashboard Stats
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Menu CRUD
        Route::get('/menus', [AdminMenuController::class, 'index'])->name('admin.menus.index');
        Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('admin.menus.create');
        Route::post('/menus', [AdminMenuController::class, 'store'])->name('admin.menus.store');
        Route::get('/menus/{id}/edit', [AdminMenuController::class, 'edit'])->name('admin.menus.edit');
        Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('admin.menus.update');
        Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('admin.menus.destroy');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile/account', [ProfileController::class, 'updateAccount'])->name('admin.profile.updateAccount');
        Route::put('/profile/store', [ProfileController::class, 'updateStore'])->name('admin.profile.updateStore');
    });

// 5. Super Admin Only Area
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->group(function () {
        // Store CRUD
        Route::get('/stores', [AdminStoreController::class, 'index'])->name('admin.stores.index');
        Route::get('/stores/create', [AdminStoreController::class, 'create'])->name('admin.stores.create');
        Route::post('/stores', [AdminStoreController::class, 'store'])->name('admin.stores.store');
        Route::get('/stores/{id}/edit', [AdminStoreController::class, 'edit'])->name('admin.stores.edit');
        Route::put('/stores/{id}', [AdminStoreController::class, 'update'])->name('admin.stores.update');
        Route::delete('/stores/{id}', [AdminStoreController::class, 'destroy'])->name('admin.stores.destroy');

        // User CRUD / Management
        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('admin.users.index');
        Route::put('/users/{id}/role', [SuperAdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
        Route::delete('/users/{id}', [SuperAdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
