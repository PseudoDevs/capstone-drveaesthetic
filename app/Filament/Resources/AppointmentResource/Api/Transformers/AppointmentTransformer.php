<?php
namespace App\Filament\Resources\AppointmentResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Appointment;

/**
 * @property Appointment $resource
 */
class AppointmentTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'client' => [
                'id' => $this->resource->client_id,
                'name' => $this->resource->client->name ?? null,
                'email' => $this->resource->client->email ?? null,
                'phone' => $this->resource->client->phone ?? null,
            ],
            'service' => [
                'id' => $this->resource->service_id,
                'name' => $this->resource->service->service_name ?? null,
                'price' => $this->resource->service->price ?? null,
                'duration' => $this->resource->service->duration ?? null,
                'description' => $this->resource->service->description ?? null,
            ],
            'staff' => [
                'id' => $this->resource->staff_id,
                'name' => $this->resource->staff->name ?? null,
            ],
            'appointment_date' => $this->resource->appointment_date,
            'appointment_time' => $this->resource->appointment_time,
            'status' => $this->resource->status,
            'is_paid' => $this->resource->is_paid,
            'is_rescheduled' => $this->resource->is_rescheduled,
            'form_type' => $this->resource->form_type,
            'form_completed' => $this->resource->form_completed,
            'medical_form_data' => $this->resource->medical_form_data,
            'consent_waiver_form_data' => $this->resource->consent_waiver_form_data,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
