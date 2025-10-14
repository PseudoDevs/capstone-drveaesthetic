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
        
        // Handle checkbox values - unchecked checkboxes don't send values
        $data = [
            'email_notifications' => $request->has('email_notifications'),
            'appointment_confirmations' => $request->has('appointment_confirmations'),
            'appointment_reminders_24h' => $request->has('appointment_reminders_24h'),
            'appointment_reminders_2h' => $request->has('appointment_reminders_2h'),
            'appointment_cancellations' => $request->has('appointment_cancellations'),
            'feedback_requests' => $request->has('feedback_requests'),
            'service_updates' => $request->has('service_updates'),
            'promotional_offers' => $request->has('promotional_offers'),
            'newsletter' => $request->has('newsletter'),
        ];
        
        $preferences = $user->getNotificationPreferences();
        $preferences->update($data);
        
        return redirect()->back()->with('success', 'Notification preferences updated successfully!');
    }
}
