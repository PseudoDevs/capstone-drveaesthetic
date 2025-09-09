<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:clinic_services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'medical_history' => 'nullable|array',
            'medical_history_other' => 'nullable|string',
            'maintenance_medications' => 'nullable|string',
            'pregnant' => 'nullable|boolean',
            'lactating' => 'nullable|boolean',
            'smoker' => 'nullable|boolean',
            'alcoholic_drinker' => 'nullable|boolean',
        ]);

        // Build medical form data
        $medicalFormData = [
            'medical_history' => $validated['medical_history'] ?? [],
            'medical_history_other' => $validated['medical_history_other'] ?? '',
            'maintenance_medications' => $validated['maintenance_medications'] ?? '',
            'pregnant' => $validated['pregnant'] ?? false,
            'lactating' => $validated['lactating'] ?? false,
            'smoker' => $validated['smoker'] ?? false,
            'alcoholic_drinker' => $validated['alcoholic_drinker'] ?? false,
        ];

        // Check for time conflicts
        if (Appointment::hasTimeConflict($validated['appointment_date'], $validated['appointment_time'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is already booked. Please select a different time.',
                    'errors' => ['appointment_time' => ['This time slot is not available.']]
                ], 422);
            }
            
            return redirect()->back()->withErrors(['appointment_time' => 'This time slot is already booked.'])->withInput();
        }

        // Create appointment data
        $appointmentData = [
            'client_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => 'pending',
            'form_type' => 'medical_information',
            'form_completed' => true,
            'medical_form_data' => $medicalFormData,
        ];

        $appointment = Appointment::create($appointmentData);

        // Return JSON response for AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully! We will contact you soon to confirm your appointment.',
                'appointment' => $appointment->load(['service', 'client'])
            ]);
        }

        return redirect()->back()->with('success', 'Appointment created successfully');
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Appointment cancelled successfully');
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Define all possible time slots (8am to 5pm)
        $allTimeSlots = [
            '08:00', '09:00', '10:00', '11:00', '12:00', 
            '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        // Get booked time slots for the date
        $bookedSlots = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_time')
            ->toArray();

        // Filter out booked slots and past slots for today
        $availableSlots = [];
        $currentHour = date('H');
        $isToday = $date === date('Y-m-d');

        foreach ($allTimeSlots as $slot) {
            $slotHour = intval(explode(':', $slot)[0]);
            
            // Skip if slot is booked
            if (in_array($slot, $bookedSlots)) {
                continue;
            }
            
            // Skip past slots for today
            if ($isToday && $slotHour <= $currentHour) {
                continue;
            }
            
            $availableSlots[] = [
                'value' => $slot,
                'label' => date('g:i A', strtotime($slot)),
                'available' => true
            ];
        }

        return response()->json([
            'success' => true,
            'available_slots' => $availableSlots,
            'booked_slots' => $bookedSlots
        ]);
    }
}