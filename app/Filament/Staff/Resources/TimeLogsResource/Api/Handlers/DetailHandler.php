<?php

namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Staff\Resources\TimeLogsResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Staff\Resources\TimeLogsResource\Api\Transformers\TimeLogsTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = TimeLogsResource::class;


    /**
     * Show TimeLogs
     *
     * @param Request $request
     * @return TimeLogsTransformer
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

        return new TimeLogsTransformer($query);
    }
}
