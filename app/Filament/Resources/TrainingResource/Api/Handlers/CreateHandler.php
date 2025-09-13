<?php
namespace App\Filament\Resources\TrainingResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\TrainingResource;
use App\Filament\Resources\TrainingResource\Api\Requests\CreateTrainingRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TrainingResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Training
     *
     * @param CreateTrainingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateTrainingRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}