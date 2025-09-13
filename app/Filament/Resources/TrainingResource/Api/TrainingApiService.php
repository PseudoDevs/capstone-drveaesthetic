<?php
namespace App\Filament\Resources\TrainingResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TrainingResource;
use Illuminate\Routing\Router;


class TrainingApiService extends ApiService
{
    protected static string | null $resource = TrainingResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
