<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicService;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MobileAppController extends Controller
{
    /**
     * Get dashboard data for mobile app
     */
    public function dashboard(): JsonResponse
    {
        $user = Auth::user();
        
        // Get user's appointments
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['service', 'staff'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_appointments' => $appointments->count(),
            'pending_appointments' => $appointments->where('status', 'pending')->count(),
            'scheduled_appointments' => $appointments->where('status', 'scheduled')->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'total_spent' => $appointments->where('status', 'completed')->sum(function($appointment) {
                return $appointment->service->price ?? 0;
            }),
            'upcoming_appointments' => $appointments->whereIn('status', ['pending', 'scheduled'])
                ->where('appointment_date', '>=', now()->format('Y-m-d'))
                ->count(),
        ];

        // Get next appointment
        $nextAppointment = $appointments->whereIn('status', ['pending', 'scheduled'])
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->sortBy('appointment_date')
            ->first();

        // Get recent appointments (last 5)
        $recentAppointments = $appointments->take(5);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar_url,
                    'phone' => $user->phone,
                ],
                'stats' => $stats,
                'next_appointment' => $nextAppointment ? [
                    'id' => $nextAppointment->id,
                    'service_name' => $nextAppointment->service->service_name,
                    'appointment_date' => $nextAppointment->appointment_date->format('Y-m-d'),
                    'appointment_time' => $nextAppointment->appointment_time,
                    'staff_name' => $nextAppointment->staff->name,
                    'status' => $nextAppointment->status,
                ] : null,
                'recent_appointments' => $recentAppointments->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'service_name' => $appointment->service->service_name,
                        'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
                        'appointment_time' => $appointment->appointment_time,
                        'status' => $appointment->status,
                        'price' => $appointment->service->price,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get services with categories for mobile app
     */
    public function services(): JsonResponse
    {
        $categories = Category::with(['clinicServices' => function($query) {
            $query->where('status', 'active');
        }])->get();

        $services = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->category_name,
                'services' => $category->clinicServices->map(function($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->service_name,
                        'description' => $service->description,
                        'price' => $service->price,
                        'duration' => $service->duration,
                        'thumbnail' => $service->thumbnail ? asset('storage/' . $service->thumbnail) : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get user's appointments with filtering
     */
    public function appointments(Request $request): JsonResponse
    {
        $user = Auth::user();
        $status = $request->get('status');
        $limit = $request->get('limit', 20);

        $query = Appointment::where('client_id', $user->id)
            ->with(['service', 'staff']);

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => [
                'appointments' => $appointments->items(),
                'pagination' => [
                    'current_page' => $appointments->currentPage(),
                    'last_page' => $appointments->lastPage(),
                    'per_page' => $appointments->perPage(),
                    'total' => $appointments->total(),
                ]
            ]
        ]);
    }

    /**
     * Get available time slots for a specific date and service
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'service_id' => 'required|exists:clinic_services,id',
        ]);

        $date = $request->get('date');
        $serviceId = $request->get('service_id');
        
        $service = ClinicService::find($serviceId);
        $duration = $service->duration;

        // Generate time slots (8 AM to 5 PM)
        $timeSlots = [];
        $startHour = 8;
        $endHour = 17;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

        // Get booked appointments for the date
        $bookedSlots = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['pending', 'scheduled'])
            ->pluck('appointment_time')
            ->toArray();

        // Filter out booked slots
        $availableSlots = array_diff($timeSlots, $bookedSlots);

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'service_duration' => $duration,
                'available_slots' => array_values($availableSlots),
                'booked_slots' => $bookedSlots,
            ]
        ]);
    }

    /**
     * Update user's notification preferences
     */
    public function updateNotificationPreferences(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'appointment_confirmations' => 'boolean',
            'appointment_reminders_24h' => 'boolean',
            'appointment_reminders_2h' => 'boolean',
            'appointment_cancellations' => 'boolean',
            'feedback_requests' => 'boolean',
            'service_updates' => 'boolean',
            'promotional_offers' => 'boolean',
            'newsletter' => 'boolean',
        ]);

        $preferences = $user->getNotificationPreferences();
        $preferences->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated successfully',
            'data' => $preferences
        ]);
    }

    /**
     * Get user's notification preferences
     */
    public function getNotificationPreferences(): JsonResponse
    {
        $user = Auth::user();
        $preferences = $user->getNotificationPreferences();

        return response()->json([
            'success' => true,
            'data' => $preferences
        ]);
    }
}
