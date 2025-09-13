<?php
namespace App\Filament\Resources\ClinicServiceResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ClinicService;

/**
 * @property ClinicService $resource
 */
class ClinicServiceTransformer extends JsonResource
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
            'service_name' => $this->resource->service_name,
            'description' => $this->resource->description,
            'thumbnail' => $this->resource->thumbnail ? asset('storage/' . $this->resource->thumbnail) : null,
            'duration' => $this->resource->duration,
            'price' => $this->resource->price,
            'status' => $this->resource->status,
            'category' => [
                'id' => $this->resource->category_id,
                'name' => $this->resource->category->name ?? null,
            ],
            'staff' => [
                'id' => $this->resource->staff_id,
                'name' => $this->resource->staff->name ?? null,
            ],
            'appointments_count' => $this->resource->appointments()->count(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
