<?php
namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TimeLogs;

/**
 * @property TimeLogs $resource
 */
class TimeLogsTransformer extends JsonResource
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
            'user' => [
                'id' => $this->resource->user_id,
                'name' => $this->resource->user->name ?? null,
                'role' => $this->resource->user->role ?? null,
            ],
            'date' => $this->resource->date,
            'clock_in' => $this->resource->clock_in,
            'clock_out' => $this->resource->clock_out,
            'total_hours' => $this->resource->total_hours,
            'is_active' => $this->resource->isActive(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
