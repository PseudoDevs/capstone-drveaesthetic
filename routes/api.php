<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\UserController;
use App\Http\Controllers\Api\Client\AppointmentController;
use App\Http\Controllers\Api\Client\ClinicServiceController;
use App\Http\Controllers\Api\Client\CategoryController;
use App\Http\Controllers\Api\Client\FeedbackController;
use App\Http\Controllers\Api\Client\ChatController;
use App\Http\Controllers\Api\Client\ChatMobileController;
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
    Route::post('users/{id}/upload-avatar', [UserController::class, 'uploadAvatar'])->name('users.upload-avatar');
    Route::delete('users/{id}/remove-avatar', [UserController::class, 'removeAvatar'])->name('users.remove-avatar');
    
    // Appointments API
    Route::apiResource('appointments', AppointmentController::class);
    Route::get('users/{userId}/appointments', [AppointmentController::class, 'getUserAppointments'])->name('appointments.user');
    
    // Clinic Services API
    Route::apiResource('services', ClinicServiceController::class);
    
    // Categories API
    Route::apiResource('categories', CategoryController::class);
    
    // Feedback API
    Route::apiResource('feedback', FeedbackController::class);
    
    // Public Chat API (no authentication required for 1-on-1 client-staff chat)
    Route::apiResource('chats', ChatController::class);
    Route::get('chats/messages/{userId}', [ChatController::class, 'getMessages'])->name('chats.get-messages');
    Route::post('chats/send-message', [ChatController::class, 'sendMessage'])->name('chats.send-message');
    
    // Mobile-specific Chat API endpoints
    Route::prefix('mobile/chat')->name('mobile.chat.')->middleware('auth:sanctum')->group(function () {
        Route::get('conversations', [ChatMobileController::class, 'getConversations'])->name('conversations');
        Route::get('conversations/{chatId}/messages', [ChatMobileController::class, 'getConversationMessages'])->name('messages');
        Route::post('send-message', [ChatMobileController::class, 'sendMessageMobile'])->name('send-message');
        Route::post('conversations/{chatId}/mark-read', [ChatMobileController::class, 'markMessagesAsRead'])->name('mark-read');
        Route::post('conversations/{chatId}/typing', [ChatMobileController::class, 'updateTypingStatus'])->name('typing');
        Route::delete('messages/{messageId}', [ChatMobileController::class, 'deleteMessage'])->name('delete-message');
        Route::get('unread-count', [ChatMobileController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('search-users', [ChatMobileController::class, 'searchUsers'])->name('search-users');
        Route::post('conversations/{chatId}/intro-message', [ChatMobileController::class, 'sendIntroMessage'])->name('intro-message');
        
        // Ajax polling endpoints for real-time chat updates
        Route::get('conversations/{chatId}/poll-messages', [ChatMobileController::class, 'pollNewMessages'])->name('poll-messages');
        Route::get('poll-conversation-updates', [ChatMobileController::class, 'pollConversationUpdates'])->name('poll-conversation-updates');
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



