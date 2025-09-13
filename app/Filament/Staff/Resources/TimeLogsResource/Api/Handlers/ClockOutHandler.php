<?php
namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Staff\Resources\TimeLogsResource;
use App\Models\TimeLogs;
use App\Models\User;
use Carbon\Carbon;

class ClockOutHandler extends Handlers {
    public static string | null $uri = '/clock-out';
    public static string | null $resource = TimeLogsResource::class;
    public static bool $public = false;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Clock Out Staff
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $user = $request->user();
        
        if (!$user || !in_array($user->role, ['Staff', 'Doctor'])) {
            return static::sendErrorResponse('Unauthorized', 401);
        }

        // Get today's active log
        $timeLog = TimeLogs::getTodaysLog($user->id);
        
        if (!$timeLog || !$timeLog->isActive()) {
            return static::sendErrorResponse('No active clock-in session found', 400);
        }

        // Clock out
        $timeLog->clock_out = now();
        $timeLog->calculateTotalHours();

        return static::sendSuccessResponse($timeLog, "Successfully clocked out");
    }
}