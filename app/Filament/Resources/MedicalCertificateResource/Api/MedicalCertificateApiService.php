<?php
namespace App\Filament\Resources\MedicalCertificateResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\MedicalCertificateResource;
use Illuminate\Routing\Router;


class MedicalCertificateApiService extends ApiService
{
    protected static string | null $resource = MedicalCertificateResource::class;

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
