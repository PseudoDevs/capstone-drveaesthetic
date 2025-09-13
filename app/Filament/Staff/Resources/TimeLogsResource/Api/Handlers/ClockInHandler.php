<?php
namespace App\Filament\Staff\Resources\TimeLogsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Staff\Resources\TimeLogsResource;
use App\Models\TimeLogs;
use App\Models\User;
use Carbon\Carbon;

class ClockInHandler extends Handlers {
    public static string | null $uri = '/clock-in';
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
     * Clock In Staff
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

        // Check if user already clocked in today
        $existingLog = TimeLogs::getTodaysLog($user->id);
        
        if ($existingLog && $existingLog->isActive()) {
            return static::sendErrorResponse('Already clocked in for today', 400);
        }

        // Create new time log
        $timeLog = new TimeLogs([
            'user_id' => $user->id,
            'clock_in' => now(),
            'date' => today(),
        ]);
        
        $timeLog->save();

        return static::sendSuccessResponse($timeLog, "Successfully clocked in");
    }
}