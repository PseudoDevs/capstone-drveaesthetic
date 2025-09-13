<?php
namespace App\Filament\Resources\AppointmentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\ClinicService;
use App\Models\User;
use App\FormType;
use Illuminate\Support\Facades\Validator;

class BookAppointmentHandler extends Handlers {
    public static string | null $uri = '/book';
    public static string | null $resource = AppointmentResource::class;
    public static bool $public = false;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Book Appointment with Form Data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:clinic_services,id',
            'staff_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'form_type' => 'required|in:' . implode(',', array_column(FormType::cases(), 'value')),
            'medical_form_data' => 'nullable|array',
            'consent_waiver_form_data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return static::sendErrorResponse($validator->errors(), 422);
        }

        $user = $request->user();
        
        if (!$user || $user->role !== 'Client') {
            return static::sendErrorResponse('Only clients can book appointments', 403);
        }

        // Check if service exists and is active
        $service = ClinicService::find($request->service_id);
        if (!$service || $service->status !== 'active') {
            return static::sendErrorResponse('Service not available', 400);
        }

        // Check if staff exists and is available
        $staff = User::find($request->staff_id);
        if (!$staff || !in_array($staff->role, ['Staff', 'Doctor'])) {
            return static::sendErrorResponse('Staff not available', 400);
        }

        // Check for time slot conflicts
        $existingAppointment = Appointment::where('staff_id', $request->staff_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return static::sendErrorResponse('Time slot already booked', 409);
        }

        // Create appointment
        $appointment = new Appointment([
            'client_id' => $user->id,
            'service_id' => $request->service_id,
            'staff_id' => $request->staff_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'scheduled',
            'is_paid' => false,
            'is_rescheduled' => false,
            'form_type' => $request->form_type,
            'form_completed' => $request->has('medical_form_data') || $request->has('consent_waiver_form_data'),
            'medical_form_data' => $request->medical_form_data,
            'consent_waiver_form_data' => $request->consent_waiver_form_data,
        ]);

        $appointment->save();

        return static::sendSuccessResponse($appointment->load(['client', 'service', 'staff']), "Appointment booked successfully");
    }
}