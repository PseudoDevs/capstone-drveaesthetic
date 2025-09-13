<?php
namespace App\Filament\Resources\FeedbackResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\FeedbackResource;
use Illuminate\Routing\Router;


class FeedbackApiService extends ApiService
{
    protected static string | null $resource = FeedbackResource::class;

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
