<?php

namespace App\Filament\Resources\AppointmentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'client_id' => 'required|exists:users,id',
			'service_id' => 'required|exists:clinic_services,id',
			'staff_id' => 'required|exists:users,id',
			'appointment_date' => 'required|date|after:today',
			'appointment_time' => 'required|date_format:H:i',
			'is_rescheduled' => 'boolean',
			'is_paid' => 'boolean',
			'status' => 'required|in:PENDING,SCHEDULED,ON-GOING,CANCELLED,DECLINED,RESCHEDULE,COMPLETED'
		];
    }
}
