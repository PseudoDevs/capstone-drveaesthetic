<?php

namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTimeLogsRequest extends FormRequest
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
			'user_id' => 'required',
			'clock_in' => 'required',
			'clock_out' => 'required',
			'total_hours' => 'required|numeric',
			'date' => 'required|date'
		];
    }
}
