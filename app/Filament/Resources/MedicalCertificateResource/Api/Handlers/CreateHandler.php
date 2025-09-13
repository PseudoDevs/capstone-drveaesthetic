<?php
namespace App\Filament\Resources\MedicalCertificateResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MedicalCertificateResource;
use App\Filament\Resources\MedicalCertificateResource\Api\Requests\CreateMedicalCertificateRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = MedicalCertificateResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create MedicalCertificate
     *
     * @param CreateMedicalCertificateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateMedicalCertificateRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}