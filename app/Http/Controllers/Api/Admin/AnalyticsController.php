<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function getOnlineUsersCount()
    {
        $count = User::where('last_activity_at', '>=', now()->subMinutes(5))
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function getRealTimeStats()
    {
        $stats = [
            'online_users' => User::where('last_activity_at', '>=', now()->subMinutes(5))->count(),
            'today_appointments' => \App\Models\Appointment::whereDate('appointment_date', today())
                ->whereIn('status', ['pending', 'scheduled'])
                ->count(),
            'today_completed' => \App\Models\Appointment::whereDate('appointment_date', today())
                ->where('status', 'completed')
                ->count(),
            'pending_appointments' => \App\Models\Appointment::where('status', 'pending')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
