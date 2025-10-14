<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationPreference;

class NotificationPreferenceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $preferences = $user->getNotificationPreferences();
        
        return view('notification-preferences', compact('preferences'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'appointment_confirmations' => 'boolean',
            'appointment_reminders_24h' => 'boolean',
            'appointment_reminders_2h' => 'boolean',
            'appointment_cancellations' => 'boolean',
            'feedback_requests' => 'boolean',
            'service_updates' => 'boolean',
            'promotional_offers' => 'boolean',
            'newsletter' => 'boolean',
        ]);
        
        $preferences = $user->getNotificationPreferences();
        $preferences->update($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Notification preferences updated successfully!');
    }
}
