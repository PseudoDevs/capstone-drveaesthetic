<?php

namespace App\Filament\Resources\MedicalCertificateResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMedicalCertificateRequest extends FormRequest
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
			'staff_id' => 'required',
			'client_id' => 'required',
			'purpose' => 'required|string',
			'amount' => 'required',
			'is_issued' => 'required'
		];
    }
}
