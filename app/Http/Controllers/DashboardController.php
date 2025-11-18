<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Payment;
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

    public function billingDashboard()
    {
        $user = Auth::user();

        // Get all bills for the user
        $bills = Bill::where('client_id', $user->id)
            ->with(['appointment.service', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate billing statistics
        $totalBills = $bills->count();
        $totalAmount = $bills->sum('total_amount');
        $paidAmount = $bills->sum('paid_amount');
        $remainingBalance = $bills->sum('remaining_balance');

        // Get bills by status
        $paidBills = $bills->where('remaining_balance', 0);
        $unpaidBills = $bills->where('remaining_balance', '>', 0);
        $partialBills = $bills->where('paid_amount', '>', 0)->where('remaining_balance', '>', 0);
        $overdueBills = $bills->where('due_date', '<', now())->where('remaining_balance', '>', 0);

        // Calculate payment progress
        $paymentProgress = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;

        // Get recent payments - check through bill client_id
        $recentPayments = Payment::whereHas('bill', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->with(['bill.appointment.service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Include paid bills that don't have payment records
        // This handles cases where appointments are marked as paid but no Payment record exists
        $paidBillsWithoutPayments = $bills->filter(function($bill) {
            return ($bill->status === 'paid' || $bill->remaining_balance <= 0) 
                && $bill->paid_amount > 0 
                && $bill->payments()->count() === 0;
        })->take(10 - $recentPayments->count());

        foreach ($paidBillsWithoutPayments as $bill) {
            // Create a virtual payment object for display
            $virtualPayment = new \stdClass();
            $virtualPayment->payment_number = $bill->bill_number ?? 'N/A';
            $virtualPayment->bill = $bill;
            $virtualPayment->amount = $bill->paid_amount;
            $virtualPayment->payment_method = 'cash'; // Default
            $virtualPayment->payment_reference = null;
            $virtualPayment->status = 'completed';
            $virtualPayment->created_at = $bill->paid_date 
                ? \Carbon\Carbon::parse($bill->paid_date) 
                : ($bill->created_at ?? now());
            $virtualPayment->is_virtual = true;
            $recentPayments->push($virtualPayment);
        }

        // Sort by date descending
        $recentPayments = $recentPayments->sortByDesc(function($payment) {
            return $payment->created_at instanceof \Carbon\Carbon 
                ? $payment->created_at 
                : \Carbon\Carbon::parse($payment->created_at);
        })->take(10);

        // Get staggered bills
        $staggeredBills = $bills->where('payment_type', 'staggered');
        $activeInstallments = $staggeredBills->where('remaining_balance', '>', 0);

        return view('billing-dashboard', compact(
            'user',
            'bills',
            'totalBills',
            'totalAmount',
            'paidAmount',
            'remainingBalance',
            'paidBills',
            'unpaidBills',
            'partialBills',
            'overdueBills',
            'paymentProgress',
            'recentPayments',
            'staggeredBills',
            'activeInstallments'
        ))->with('pendingBills', $unpaidBills);
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
