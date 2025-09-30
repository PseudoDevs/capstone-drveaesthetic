<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class RealTimeStatsWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.real-time-stats';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // Get real-time statistics
        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->whereIn('status', ['pending', 'scheduled'])
            ->count();

        $todayCompleted = Appointment::whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();

        // Try to get online users count, fallback to total users if column doesn't exist
        try {
            $onlineUsers = User::where('last_activity_at', '>=', now()->subMinutes(5))
                ->count();
        } catch (\Exception $e) {
            // Fallback to using updated_at if last_activity_at doesn't exist
            $onlineUsers = User::where('updated_at', '>=', now()->subMinutes(5))
                ->count();
        }

        $pendingAppointments = Appointment::where('status', 'pending')
            ->count();

        // Get recent activity
        $recentAppointments = Appointment::with(['client', 'service', 'staff'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'todayAppointments' => $todayAppointments,
            'todayCompleted' => $todayCompleted,
            'onlineUsers' => $onlineUsers,
            'pendingAppointments' => $pendingAppointments,
            'recentAppointments' => $recentAppointments,
        ];
    }
}
