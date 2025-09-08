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
            'pending' => $appointments->where('status', 'PENDING'),
            'scheduled' => $appointments->where('status', 'SCHEDULED'),
            'completed' => $appointments->where('status', 'COMPLETED'),
            'cancelled' => $appointments->where('status', 'CANCELLED'),
        ];

        // Get appointments for calendar (scheduled and pending only)
        $calendarAppointments = $appointments->whereIn('status', ['PENDING', 'SCHEDULED'])
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->service_name ?? 'Appointment',
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . date('H:i:s', strtotime($appointment->appointment_time . ' +1 hour')),
                    'backgroundColor' => $appointment->status === 'SCHEDULED' ? '#28a745' : '#ffc107',
                    'borderColor' => $appointment->status === 'SCHEDULED' ? '#28a745' : '#ffc107',
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

        return view('dashboard', compact('user', 'appointmentsByStatus', 'calendarAppointments'));
    }

    public function getAppointments(Request $request)
    {
        $user = Auth::user();
        
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['service', 'staff'])
            ->whereIn('status', ['PENDING', 'SCHEDULED'])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->service_name ?? 'Appointment',
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . date('H:i:s', strtotime($appointment->appointment_time . ' +1 hour')),
                    'backgroundColor' => $appointment->status === 'SCHEDULED' ? '#28a745' : '#ffc107',
                    'borderColor' => $appointment->status === 'SCHEDULED' ? '#28a745' : '#ffc107',
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
        
        $request->validate([
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
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
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
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Check if this is an AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating your profile.',
                    'errors' => ['general' => ['Please try again.']]
                ], 422);
            }
            
            // Regular form submission
            return redirect()->back()->withErrors(['general' => 'An error occurred while updating your profile. Please try again.']);
        }
    }
}
