<?php
namespace App\Filament\Resources\ClinicServiceResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ClinicServiceResource;
use App\Filament\Resources\ClinicServiceResource\Api\Requests\UpdateClinicServiceRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ClinicServiceResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update ClinicService
     *
     * @param UpdateClinicServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateClinicServiceRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}