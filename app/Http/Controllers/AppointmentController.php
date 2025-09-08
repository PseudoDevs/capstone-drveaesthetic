<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:clinic_services,id',
            'staff_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'form_type' => 'nullable|string',
            'medical_form_data' => 'nullable|array',
            'consent_waiver_form_data' => 'nullable|array',
        ]);

        $appointment = Appointment::create($validated);

        return redirect()->back()->with('success', 'Appointment created successfully');
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Appointment cancelled successfully');
    }
}