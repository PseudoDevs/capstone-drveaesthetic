<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        // Get appointments for the authenticated user
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['service', 'staff'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Group appointments by status
        $appointmentsByStatus = [
            'pending' => $appointments->where('status', 'pending'),
            'scheduled' => $appointments->where('status', 'scheduled'),
            'completed' => $appointments->where('status', 'completed'),
            'cancelled' => $appointments->where('status', 'cancelled'),
        ];

        // Calculate additional statistics
        $totalAppointments = $appointments->count();
        $totalSpent = $appointments->where('status', 'completed')
            ->sum(function($appointment) {
                return $appointment->service->price ?? 0;
            });
        
        $upcomingAppointments = $appointments->whereIn('status', ['pending', 'scheduled'])
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->count();
            
        $thisMonthAppointments = $appointments->filter(function($appointment) {
            return \Carbon\Carbon::parse($appointment->appointment_date)->isSameMonth(now());
        })->count();

        // Get next appointment
        $nextAppointment = $appointments->whereIn('status', ['pending', 'scheduled'])
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->sortBy('appointment_date')
            ->first();

        // Get most popular service
        $popularService = $appointments->where('status', 'completed')
            ->groupBy('service_id')
            ->map(function($group) {
                return [
                    'service' => $group->first()->service,
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->first();

        // Get appointments for calendar (scheduled and pending only)
        $calendarAppointments = $appointments->whereIn('status', ['pending', 'scheduled'])
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->service_name ?? 'Appointment',
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . date('H:i:s', strtotime($appointment->appointment_time . ' +1 hour')),
                    'backgroundColor' => $appointment->status === 'scheduled' ? '#28a745' : '#ffc107',
                    'borderColor' => $appointment->status === 'scheduled' ? '#28a745' : '#ffc107',
                    'textColor' => '#fff',
                    'extendedProps' => [
                        'status' => $appointment->status,
                        'service' => $appointment->service->service_name ?? 'N/A',
                        'staff' => $appointment->staff->name ?? 'N/A',
                        'date' => $appointment->appointment_date->format('F j, Y'),
                        'time' => date('h:i A', strtotime($appointment->appointment_time)),
                        'price' => '₱' . number_format($appointment->service->price ?? 0, 2)
                    ]
                ];
            });

        return view('dashboard', compact(
            'user', 
            'appointmentsByStatus', 
            'calendarAppointments',
            'totalAppointments',
            'totalSpent',
            'upcomingAppointments',
            'thisMonthAppointments',
            'nextAppointment',
            'popularService'
        ));
    }

    public function getAppointments(Request $request)
    {
        $user = Auth::user();
        
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['service', 'staff'])
            ->whereIn('status', ['pending', 'scheduled'])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->service_name ?? 'Appointment',
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . date('H:i:s', strtotime($appointment->appointment_time . ' +1 hour')),
                    'backgroundColor' => $appointment->status === 'scheduled' ? '#28a745' : '#ffc107',
                    'borderColor' => $appointment->status === 'scheduled' ? '#28a745' : '#ffc107',
                    'textColor' => '#fff',
                    'extendedProps' => [
                        'status' => $appointment->status,
                        'service' => $appointment->service->service_name ?? 'N/A',
                        'staff' => $appointment->staff->name ?? 'N/A',
                        'date' => $appointment->appointment_date->format('F j, Y'),
                        'time' => date('h:i A', strtotime($appointment->appointment_time)),
                        'price' => '₱' . number_format($appointment->service->price ?? 0, 2)
                    ]
                ];
            });

        return response()->json($appointments);
    }

    public function editProfile()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Only add password validation if user wants to change password
        if ($request->filled('new_password')) {
            $validationRules['current_password'] = 'required|current_password';
            $validationRules['new_password'] = 'required|min:8|confirmed';
        }

        $request->validate($validationRules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists and is not a Google avatar URL
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        // Handle password update
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        try {
            $user->update($updateData);
            
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!'
                ]);
            }
            
            // Regular form submission
            return redirect()->route('users.profile.edit')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->except(['avatar', 'current_password', 'new_password', 'new_password_confirmation'])
            ]);
            
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating your profile: ' . $e->getMessage(),
                    'errors' => ['general' => ['Please try again.']]
                ], 422);
            }
            
            // Regular form submission
            return redirect()->back()
                ->withErrors(['general' => 'An error occurred while updating your profile. Please try again.'])
                ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
        }
    }
}
