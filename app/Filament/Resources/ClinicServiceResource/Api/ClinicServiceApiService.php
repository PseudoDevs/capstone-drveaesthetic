<?php
namespace App\Filament\Resources\ClinicServiceResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ClinicServiceResource;
use Illuminate\Routing\Router;


class ClinicServiceApiService extends ApiService
{
    protected static string | null $resource = ClinicServiceResource::class;

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
