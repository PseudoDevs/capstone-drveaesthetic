<?php
namespace App\Filament\Resources\FeedbackResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Feedback;

/**
 * @property Feedback $resource
 */
class FeedbackTransformer extends JsonResource
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
            'rating' => $this->resource->rating,
            'comment' => $this->resource->comment,
            'client' => [
                'id' => $this->resource->client_id,
                'name' => $this->resource->client->name ?? null,
                'email' => $this->resource->client->email ?? null,
            ],
            'appointment' => [
                'id' => $this->resource->appointment_id,
                'appointment_date' => $this->resource->appointment->appointment_date ?? null,
                'service_name' => $this->resource->appointment->service->service_name ?? null,
            ],
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
