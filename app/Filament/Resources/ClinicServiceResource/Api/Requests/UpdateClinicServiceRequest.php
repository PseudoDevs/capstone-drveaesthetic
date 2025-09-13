<?php

namespace App\Filament\Resources\ClinicServiceResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicServiceRequest extends FormRequest
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
			'category_id' => 'required',
			'staff_id' => 'required',
			'service_name' => 'required|string',
			'description' => 'required|string',
			'thumbnail' => 'required',
			'duration' => 'required|string',
			'price' => 'required',
			'status' => 'required'
		];
    }
}
