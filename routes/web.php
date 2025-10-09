<?php

use App\Models\ClinicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    $categoryId = request('category');
    
    $query = ClinicService::with('category', 'staff')->where('status', 'active');
    
    // Filter by category if specified
    if ($categoryId && $categoryId !== 'all') {
        $query->where('category_id', $categoryId);
    }
    
    $services = $query->paginate(6);
    return view('services', compact('services'));
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/notification-preferences', function () {
    return view('notification-preferences');
})->name('notification-preferences')->middleware('auth');

Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create')->middleware('auth');
Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.submit')->middleware('auth');
Route::get('/feedback/{id}', [\App\Http\Controllers\FeedbackController::class, 'show'])->name('feedback.show');

// Form completion routes
Route::get('/appointments/{appointment}/form', [\App\Http\Controllers\FormController::class, 'showMedicalForm'])->name('appointments.form')->middleware('auth');
Route::post('/appointments/{appointment}/form', [\App\Http\Controllers\FormController::class, 'completeForm'])->name('appointments.complete-form')->middleware('auth');
Route::get('/appointments/{appointment}/form/view', [\App\Http\Controllers\FormController::class, 'viewCompletedForm'])->name('appointments.form.view')->middleware('auth');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

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

});

// Public Chat Routes (no authentication required)
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/conversations', [ChatController::class, 'getConversations'])->name('conversations');
    Route::get('/messages/{userId}', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send');
    
    // Ajax polling endpoints for real-time chat updates
    Route::get('/poll-messages/{chatId}', [ChatController::class, 'pollNewMessages'])->name('poll-messages');
    Route::get('/poll-conversation-updates', [ChatController::class, 'pollConversationUpdates'])->name('poll-conversation-updates');
});

// Medical Certificate Download Route (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/medical-certificate/{id}/download', function ($id) {
        $certificate = \App\Models\MedicalCertificate::with(['client', 'staff'])->findOrFail($id);

        $clientName = $certificate->client ? $certificate->client->name : 'Unknown-Client';
        
        return response()->streamDownload(function () use ($certificate) {
            echo \App\Filament\Resources\MedicalCertificateResource::generateCertificatePDF($certificate);
        }, "medical-certificate-{$clientName}-" . now()->format('Y-m-d') . ".pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    })->name('medical-certificate.download');
});
