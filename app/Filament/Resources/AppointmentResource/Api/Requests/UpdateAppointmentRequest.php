<?php

namespace App\Filament\Resources\AppointmentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
			'client_id' => 'required',
			'service_id' => 'required',
			'staff_id' => 'required',
			'appointment_date' => 'required|date',
			'appointment_time' => 'required|string',
			'is_rescheduled' => 'required',
			'is_paid' => 'required',
			'status' => 'required'
		];
    }
}
