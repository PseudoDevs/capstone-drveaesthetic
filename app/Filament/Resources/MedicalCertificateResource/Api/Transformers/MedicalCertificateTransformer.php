<?php
namespace App\Filament\Resources\MedicalCertificateResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MedicalCertificate;

/**
 * @property MedicalCertificate $resource
 */
class MedicalCertificateTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
