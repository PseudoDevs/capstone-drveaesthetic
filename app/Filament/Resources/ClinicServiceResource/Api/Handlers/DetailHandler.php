<?php

namespace App\Filament\Resources\ClinicServiceResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ClinicServiceResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ClinicServiceResource\Api\Transformers\ClinicServiceTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ClinicServiceResource::class;


    /**
     * Show ClinicService
     *
     * @param Request $request
     * @return ClinicServiceTransformer
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

        return new ClinicServiceTransformer($query);
    }
}
