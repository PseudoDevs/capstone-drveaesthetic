<?php
namespace App\Filament\Resources\AppointmentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AvailableTimeSlotsHandler extends Handlers {
    public static string | null $uri = '/available-slots';
    public static string | null $resource = AppointmentResource::class;
    public static bool $public = false;

    public static function getMethod()
    {
        return Handlers::GET;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Get Available Time Slots
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $staffId = $request->query('staff_id');
        $date = $request->query('date');

        if (!$staffId || !$date) {
            return static::sendErrorResponse('staff_id and date parameters are required', 400);
        }

        // Validate date format
        try {
            $appointmentDate = Carbon::parse($date);
        } catch (\Exception $e) {
            return static::sendErrorResponse('Invalid date format', 400);
        }

        // Check if staff exists
        $staff = User::find($staffId);
        if (!$staff || !in_array($staff->role, ['Staff', 'Doctor'])) {
            return static::sendErrorResponse('Staff not found', 404);
        }

        // Define working hours (9 AM to 6 PM)
        $workingHours = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            $workingHours[] = sprintf('%02d:00', $hour);
            if ($hour < 17) { // Don't add 6:30 PM
                $workingHours[] = sprintf('%02d:30', $hour);
            }
        }

        // Get booked time slots for the date
        $bookedSlots = Appointment::where('staff_id', $staffId)
            ->where('appointment_date', $appointmentDate->format('Y-m-d'))
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->pluck('appointment_time')
            ->toArray();

        // Filter available slots
        $availableSlots = array_diff($workingHours, $bookedSlots);

        // If date is today, remove past time slots
        if ($appointmentDate->isToday()) {
            $currentTime = now()->format('H:i');
            $availableSlots = array_filter($availableSlots, function($slot) use ($currentTime) {
                return $slot > $currentTime;
            });
        }

        return static::sendSuccessResponse([
            'date' => $appointmentDate->format('Y-m-d'),
            'staff_id' => $staffId,
            'staff_name' => $staff->name,
            'available_slots' => array_values($availableSlots),
            'booked_slots' => $bookedSlots,
        ], "Available time slots retrieved successfully");
    }
}