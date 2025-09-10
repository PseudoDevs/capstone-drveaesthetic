<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['client', 'service', 'staff'])->get();
        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    public function show($id): JsonResponse
    {
        $appointment = Appointment::with(['client', 'service', 'staff'])->find($id);
        
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:clinic_services,id',
            'staff_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'form_type' => 'nullable|string',
            'medical_form_data' => 'nullable|array',
            'consent_waiver_form_data' => 'nullable|array',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment->load(['client', 'service', 'staff'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        $validated = $request->validate([
            'client_id' => 'sometimes|exists:users,id',
            'service_id' => 'sometimes|exists:clinic_services,id',
            'staff_id' => 'nullable|exists:users,id',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes|string',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'is_rescheduled' => 'sometimes|boolean',
            'is_paid' => 'sometimes|boolean',
            'form_completed' => 'sometimes|boolean',
            'form_type' => 'nullable|string',
            'medical_form_data' => 'nullable|array',
            'consent_waiver_form_data' => 'nullable|array',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment->load(['client', 'service', 'staff'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully'
        ]);
    }

    public function getUserAppointments($userId): JsonResponse
    {
        $appointments = Appointment::with(['client', 'service', 'staff'])
            ->forClient($userId)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
}