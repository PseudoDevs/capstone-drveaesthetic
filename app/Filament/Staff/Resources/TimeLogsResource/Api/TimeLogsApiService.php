<?php
namespace App\Filament\Staff\Resources\TimeLogsResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Staff\Resources\TimeLogsResource;
use Illuminate\Routing\Router;


class TimeLogsApiService extends ApiService
{
    protected static string | null $resource = TimeLogsResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
            Handlers\ClockInHandler::class,
            Handlers\ClockOutHandler::class,
        ];

    }
}
