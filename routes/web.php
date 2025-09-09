<?php

use App\Models\ClinicService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    $services = ClinicService::with('category', 'staff')->where('status', 'active')->paginate(6);
    return view('services', compact('services'));
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/users/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/users/register', [LoginController::class, 'showRegisterForm'])->name('register');
});

// Authentication actions (with CSRF protection and rate limiting)
Route::post('/users/login', [LoginController::class, 'login'])
    ->name('users.authenticate');

Route::post('/users/register', [LoginController::class, 'register'])
    ->name('users.create-account');

// Logout (must be authenticated)
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


    Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [LoginController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('google.callback');

    // Debug route for OAuth issues
    Route::get('/clear-session', function() {
        session()->flush();
        return redirect('/users/login')->with('success', 'Session cleared. Please try logging in again.');
    })->name('clear-session');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/appointments', [DashboardController::class, 'getAppointments'])->name('dashboard.appointments');

        // Profile Routes
        Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [DashboardController::class, 'update'])->name('profile.update');
    });

    // Appointment Routes
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::post('/', [AppointmentController::class, 'store'])->name('store')->middleware('auth');
        Route::patch('/{id}/cancel', [AppointmentController::class, 'cancel'])->name('cancel')->middleware('auth');
    });
    
    // Public appointment routes (no auth required)
    Route::get('/appointments/available-slots', [AppointmentController::class, 'getAvailableTimeSlots'])->name('appointments.available-slots');

    // Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/conversations', [ChatController::class, 'getConversations'])->name('conversations');
        Route::get('/messages/{userId}', [ChatController::class, 'getMessages'])->name('messages');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
    });
});
