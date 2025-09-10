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
    Route::get('users/{userId}/appointments', [AppointmentController::class, 'getUserAppointments'])->name('appointments.user');
    
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
    
    // Mobile-specific Chat API endpoints
    Route::prefix('mobile/chat')->name('mobile.chat.')->middleware('auth:sanctum')->group(function () {
        Route::get('conversations', [ChatController::class, 'getConversations'])->name('conversations');
        Route::get('conversations/{chatId}/messages', [ChatController::class, 'getConversationMessages'])->name('messages');
        Route::post('send-message', [ChatController::class, 'sendMessageMobile'])->name('send-message');
        Route::post('conversations/{chatId}/mark-read', [ChatController::class, 'markMessagesAsRead'])->name('mark-read');
        Route::post('conversations/{chatId}/typing', [ChatController::class, 'updateTypingStatus'])->name('typing');
        Route::delete('messages/{messageId}', [ChatController::class, 'deleteMessage'])->name('delete-message');
        Route::get('unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('search-users', [ChatController::class, 'searchUsers'])->name('search-users');
    });
    
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

// Special routes that support Bearer token authentication for mobile chat functionality
Route::prefix('client')->name('client.')->middleware('auth:sanctum')->group(function () {
    Route::get('chats/search/staff', [ChatController::class, 'searchStaff'])->name('chats.search-staff');
});


