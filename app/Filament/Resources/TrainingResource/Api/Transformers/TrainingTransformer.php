<?php
namespace App\Filament\Resources\TrainingResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Training;

/**
 * @property Training $resource
 */
class TrainingTransformer extends JsonResource
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
            'title' => $this->resource->title,
            'type' => $this->resource->type,
            'description' => $this->resource->description,
            'thumbnail' => $this->resource->thumbnail ? asset('storage/' . $this->resource->thumbnail) : null,
            'is_published' => $this->resource->is_published,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
