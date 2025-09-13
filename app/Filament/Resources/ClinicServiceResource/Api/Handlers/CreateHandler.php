<?php
namespace App\Filament\Resources\ClinicServiceResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ClinicServiceResource;
use App\Filament\Resources\ClinicServiceResource\Api\Requests\CreateClinicServiceRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ClinicServiceResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create ClinicService
     *
     * @param CreateClinicServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateClinicServiceRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}