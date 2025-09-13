<?php
namespace App\Filament\Resources\FeedbackResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\FeedbackResource;
use App\Filament\Resources\FeedbackResource\Api\Requests\UpdateFeedbackRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = FeedbackResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Feedback
     *
     * @param UpdateFeedbackRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateFeedbackRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}