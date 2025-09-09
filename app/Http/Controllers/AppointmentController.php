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

        // Get the service to retrieve staff_id
        $service = \App\Models\ClinicService::findOrFail($validated['service_id']);
        
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

        // Add staff_id if the service has one assigned
        if ($service->staff_id) {
            $appointmentData['staff_id'] = $service->staff_id;
        }

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

    public function cancel(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            // Check if the appointment belongs to the authenticated user
            if ($appointment->client_id !== auth()->id()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to cancel this appointment.'
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'You are not authorized to cancel this appointment.']);
            }
            
            // Check if appointment can be cancelled
            if ($appointment->status === 'cancelled') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This appointment is already cancelled.'
                    ], 422);
                }
                return redirect()->back()->withErrors(['error' => 'This appointment is already cancelled.']);
            }
            
            if ($appointment->status === 'completed') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot cancel a completed appointment.'
                    ], 422);
                }
                return redirect()->back()->withErrors(['error' => 'Cannot cancel a completed appointment.']);
            }
            
            $appointment->update(['status' => 'cancelled']);

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment cancelled successfully!',
                    'appointment' => $appointment->load(['service', 'client'])
                ]);
            }

            return redirect()->back()->with('success', 'Appointment cancelled successfully!');
            
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while cancelling the appointment.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'An error occurred while cancelling the appointment.']);
        }
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