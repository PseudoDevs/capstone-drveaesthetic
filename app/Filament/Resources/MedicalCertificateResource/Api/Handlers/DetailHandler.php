<?php

namespace App\Filament\Resources\MedicalCertificateResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\MedicalCertificateResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\MedicalCertificateResource\Api\Transformers\MedicalCertificateTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = MedicalCertificateResource::class;


    /**
     * Show MedicalCertificate
     *
     * @param Request $request
     * @return MedicalCertificateTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new MedicalCertificateTransformer($query);
    }
}
