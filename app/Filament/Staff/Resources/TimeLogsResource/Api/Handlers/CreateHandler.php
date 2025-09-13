<?php
namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Staff\Resources\TimeLogsResource;
use App\Filament\Staff\Resources\TimeLogsResource\Api\Requests\CreateTimeLogsRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TimeLogsResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create TimeLogs
     *
     * @param CreateTimeLogsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateTimeLogsRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}