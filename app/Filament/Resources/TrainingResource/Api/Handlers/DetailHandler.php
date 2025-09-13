<?php

namespace App\Filament\Resources\TrainingResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\TrainingResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\TrainingResource\Api\Transformers\TrainingTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = TrainingResource::class;


    /**
     * Show Training
     *
     * @param Request $request
     * @return TrainingTransformer
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

        return new TrainingTransformer($query);
    }
}
