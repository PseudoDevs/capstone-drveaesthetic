<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create()
    {
        // Redirect to login if not authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to leave feedback.');
        }

        return view('feedback.create');
    }

    public function store(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to leave feedback.');
        }

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'anonymous' => 'nullable|boolean',
        ]);

        // Verify the appointment belongs to the authenticated user and is completed
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('client_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$appointment) {
            return back()->withErrors(['appointment_id' => 'Invalid appointment or appointment not completed.']);
        }

        // Check if feedback already exists for this appointment
        $existingFeedback = Feedback::where('appointment_id', $request->appointment_id)
            ->where('client_id', Auth::id())
            ->first();

        if ($existingFeedback) {
            return back()->withErrors(['appointment_id' => 'You have already submitted feedback for this appointment.']);
        }

        // Create the feedback
        $feedback = Feedback::create([
            'client_id' => Auth::id(),
            'appointment_id' => $request->appointment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('feedback.create')->with('success', 
            'Thank you for your feedback! Your review helps us improve our services and helps other clients make informed decisions.'
        );
    }

    public function show($id)
    {
        // This could be used to show individual feedback details
        $feedback = Feedback::with(['client', 'appointment.service'])
            ->findOrFail($id);

        return view('feedback.show', compact('feedback'));
    }
}
