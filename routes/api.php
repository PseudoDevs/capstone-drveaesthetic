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
use App\Http\Controllers\Api\Client\BillingController;
use App\Http\Controllers\Api\Client\PrescriptionController;
use App\Http\Controllers\Api\Mobile\GoogleAuthController as MobileGoogleAuthController;
use App\Http\Controllers\Api\Web\GoogleAuthController as WebGoogleAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin Analytics API Routes
Route::prefix('admin')->name('admin.')->middleware('auth:sanctum')->group(function () {
    Route::get('online-users-count', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'getOnlineUsersCount'])->name('online-users-count');
    Route::get('real-time-stats', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'getRealTimeStats'])->name('real-time-stats');
});

// Public Authentication Routes (no auth required)
Route::prefix('client/auth')->name('client.auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('google-login', [AuthController::class, 'googleLogin'])->name('google-login');
});

// Web Google Authentication Routes (no auth required)
Route::prefix('web/auth/google')->name('web.auth.google.')->group(function () {
    Route::get('redirect', [WebGoogleAuthController::class, 'redirectToGoogle'])->name('redirect');
    Route::get('callback', [WebGoogleAuthController::class, 'handleGoogleCallback'])->name('callback');
});

// Mobile Google Authentication Routes (no auth required)
Route::prefix('mobile/auth/google')->name('mobile.auth.google.')->group(function () {
    Route::post('token', [MobileGoogleAuthController::class, 'authenticateWithToken'])->name('token');
    Route::post('id-token', [MobileGoogleAuthController::class, 'authenticateWithIdToken'])->name('id-token');
});

// Mobile Google Authentication Routes (auth required)
Route::prefix('mobile/auth/google')->name('mobile.auth.google.')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [MobileGoogleAuthController::class, 'logout'])->name('logout');
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
    
    // Billing API
    Route::prefix('billing')->name('billing.')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [BillingController::class, 'dashboard'])->name('dashboard');
        Route::get('history', [BillingController::class, 'paymentHistory'])->name('history');
        Route::get('outstanding', [BillingController::class, 'outstandingBalance'])->name('outstanding');
        Route::post('pay', [BillingController::class, 'processPayment'])->name('pay');
    });
    
    // Bills API
    Route::prefix('bills')->name('bills.')->middleware('auth:sanctum')->group(function () {
        Route::get('users/{clientId}', [\App\Http\Controllers\Api\Client\BillApiController::class, 'getUserBills'])->name('user-bills');
        Route::get('{billId}', [\App\Http\Controllers\Api\Client\BillApiController::class, 'show'])->name('show');
        Route::get('users/{clientId}/outstanding-balance', [\App\Http\Controllers\Api\Client\BillApiController::class, 'getOutstandingBalance'])->name('outstanding-balance');
        Route::get('{billId}/payment-history', [\App\Http\Controllers\Api\Client\BillApiController::class, 'getPaymentHistory'])->name('payment-history');
        Route::get('{billId}/receipt', [\App\Http\Controllers\Api\Client\BillApiController::class, 'getReceipt'])->name('receipt');
    });
    
    // Payments API
    Route::prefix('payments')->name('payments.')->middleware('auth:sanctum')->group(function () {
        Route::get('users/{clientId}', [\App\Http\Controllers\Api\Client\PaymentApiController::class, 'getUserPayments'])->name('user-payments');
        Route::get('{paymentId}', [\App\Http\Controllers\Api\Client\PaymentApiController::class, 'show'])->name('show');
        Route::post('/', [\App\Http\Controllers\Api\Client\PaymentApiController::class, 'store'])->name('store');
        Route::get('users/{clientId}/summary', [\App\Http\Controllers\Api\Client\PaymentApiController::class, 'getPaymentSummary'])->name('summary');
        Route::get('{paymentId}/receipt', [\App\Http\Controllers\Api\Client\PaymentApiController::class, 'getReceipt'])->name('receipt');
    });
    
    // Prescriptions API
    Route::prefix('prescriptions')->name('prescriptions.')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('index');
        Route::get('statistics', [PrescriptionController::class, 'statistics'])->name('statistics');
        Route::get('{id}', [PrescriptionController::class, 'show'])->name('show');
        Route::get('{id}/download', [PrescriptionController::class, 'download'])->name('download');
    });
    
    // Time Logs API
    Route::apiResource('time-logs', TimeLogsController::class);
    Route::post('time-logs/clock-in', [TimeLogsController::class, 'clockIn'])->name('time-logs.clock-in');
    Route::post('time-logs/clock-out', [TimeLogsController::class, 'clockOut'])->name('time-logs.clock-out');
    
    // Training API
    Route::apiResource('trainings', TrainingController::class);
    Route::get('trainings/published/list', [TrainingController::class, 'published'])->name('trainings.published');
    
    // Mobile App API Routes
    Route::prefix('mobile')->name('mobile.')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'dashboard'])->name('dashboard');
        Route::get('services', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'services'])->name('services');
        Route::get('appointments', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'appointments'])->name('appointments');
        Route::get('available-slots', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'availableSlots'])->name('available-slots');
        Route::get('notification-preferences', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'getNotificationPreferences'])->name('notification-preferences');
        Route::put('notification-preferences', [\App\Http\Controllers\Api\Mobile\MobileAppController::class, 'updateNotificationPreferences'])->name('notification-preferences.update');
    });
    
});



