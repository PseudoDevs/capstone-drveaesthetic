<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\UserController;
use App\Http\Controllers\Api\Client\AppointmentController;
use App\Http\Controllers\Api\Client\ClinicServiceController;
use App\Http\Controllers\Api\Client\CategoryController;
use App\Http\Controllers\Api\Client\FeedbackController;
use App\Http\Controllers\Api\Client\ChatController;
use App\Http\Controllers\Api\Client\MessageController;
use App\Http\Controllers\Api\Client\MedicalCertificateController;
use App\Http\Controllers\Api\Client\TimeLogsController;
use App\Http\Controllers\Api\Client\TrainingController;
use App\Http\Controllers\Api\Client\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Authentication Routes (no auth required)
Route::prefix('client/auth')->name('client.auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('google-login', [AuthController::class, 'googleLogin'])->name('google-login');
});

// Protected Authentication Routes (auth required)
Route::prefix('client/auth')->name('client.auth.')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('unlink-google', [AuthController::class, 'unlinkGoogle'])->name('unlink-google');
});

// Client API Routes with /api/client prefix
Route::prefix('client')->name('client.')->group(function () {
    
    // Users API
    Route::apiResource('users', UserController::class);
    
    // Appointments API
    Route::apiResource('appointments', AppointmentController::class);
    
    // Clinic Services API
    Route::apiResource('services', ClinicServiceController::class);
    
    // Categories API
    Route::apiResource('categories', CategoryController::class);
    
    // Feedback API
    Route::apiResource('feedback', FeedbackController::class);
    
    // Chat API
    Route::apiResource('chats', ChatController::class);
    Route::get('chats/messages/{userId}', [ChatController::class, 'getMessages'])->name('chats.get-messages');
    Route::post('chats/send-message', [ChatController::class, 'sendMessage'])->name('chats.send-message');
    
    // Messages API
    Route::apiResource('messages', MessageController::class);
    
    // Medical Certificates API
    Route::apiResource('medical-certificates', MedicalCertificateController::class);
    
    // Time Logs API
    Route::apiResource('time-logs', TimeLogsController::class);
    Route::post('time-logs/clock-in', [TimeLogsController::class, 'clockIn'])->name('time-logs.clock-in');
    Route::post('time-logs/clock-out', [TimeLogsController::class, 'clockOut'])->name('time-logs.clock-out');
    
    // Training API
    Route::apiResource('trainings', TrainingController::class);
    Route::get('trainings/published/list', [TrainingController::class, 'published'])->name('trainings.published');
    
});

// Special routes that support web authentication for chat functionality
Route::prefix('client')->name('client.')->middleware('web')->group(function () {
    Route::get('chats/search/staff', [ChatController::class, 'searchStaff'])->name('chats.search-staff');
});
