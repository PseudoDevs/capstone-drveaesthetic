<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BillApiController extends Controller
{
    /**
     * Get all bills for a specific client
     */
    public function getUserBills($clientId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user can access this client's bills
        if ($user->id != $clientId && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client bills'
            ], 403);
        }

        $bills = Bill::where('client_id', $clientId)
            ->with(['appointment.service', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $billsData = $bills->map(function($bill) {
            return [
                'id' => $bill->id,
                'bill_number' => $bill->bill_number,
                'client_id' => $bill->client_id,
                'appointment_id' => $bill->appointment_id,
                'total_amount' => $bill->total_amount,
                'paid_amount' => $bill->paid_amount,
                'remaining_balance' => $bill->remaining_balance,
                'status' => $bill->status,
                'due_date' => $bill->due_date,
                'is_overdue' => $bill->due_date < now()->format('Y-m-d') && $bill->remaining_balance > 0,
                'payment_progress' => $bill->total_amount > 0 ? round(($bill->paid_amount / $bill->total_amount) * 100, 2) : 0,
                'is_fully_paid' => $bill->remaining_balance <= 0,
                'is_staggered_payment' => $bill->payment_type === 'staggered',
                'next_payment_amount' => $bill->payment_type === 'staggered' ? $bill->next_payment_amount : $bill->remaining_balance,
                'appointment' => $bill->appointment ? [
                    'id' => $bill->appointment->id,
                    'appointment_date' => $bill->appointment->appointment_date,
                    'service' => [
                        'id' => $bill->appointment->service->id,
                        'service_name' => $bill->appointment->service->service_name,
                        'price' => $bill->appointment->service->price
                    ]
                ] : null,
                'payments' => $bill->payments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'status' => $payment->status,
                        'payment_date' => $payment->payment_date
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $billsData
        ]);
    }

    /**
     * Get specific bill details
     */
    public function show($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::with(['appointment.service', 'payments', 'client'])
            ->find($billId);

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'Bill not found'
            ], 404);
        }

        // Check if user can access this bill
        if ($bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this bill'
            ], 403);
        }

        $billData = [
            'id' => $bill->id,
            'bill_number' => $bill->bill_number,
            'total_amount' => $bill->total_amount,
            'paid_amount' => $bill->paid_amount,
            'remaining_balance' => $bill->remaining_balance,
            'status' => $bill->status,
            'payment_progress' => $bill->total_amount > 0 ? round(($bill->paid_amount / $bill->total_amount) * 100, 2) : 0,
            'is_fully_paid' => $bill->remaining_balance <= 0,
            'client' => [
                'id' => $bill->client->id,
                'name' => $bill->client->name,
                'email' => $bill->client->email
            ],
            'appointment' => $bill->appointment ? [
                'id' => $bill->appointment->id,
                'service' => [
                    'service_name' => $bill->appointment->service->service_name,
                    'price' => $bill->appointment->service->price
                ]
            ] : null,
            'payments' => $bill->payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'status' => $payment->status,
                    'payment_date' => $payment->payment_date
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $billData
        ]);
    }

    /**
     * Get outstanding balance for a client
     */
    public function getOutstandingBalance($clientId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user can access this client's data
        if ($user->id != $clientId && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client data'
            ], 403);
        }

        $bills = Bill::where('client_id', $clientId)->get();
        
        $totalOutstanding = $bills->sum('remaining_balance');
        $overdueAmount = $bills->where('due_date', '<', now()->format('Y-m-d'))
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');
        $pendingAmount = $bills->where('due_date', '>=', now()->format('Y-m-d'))
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');
        $partialAmount = $bills->where('paid_amount', '>', 0)
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');

        $overdueCount = $bills->where('due_date', '<', now()->format('Y-m-d'))
            ->where('remaining_balance', '>', 0)
            ->count();
        $pendingCount = $bills->where('due_date', '>=', now()->format('Y-m-d'))
            ->where('remaining_balance', '>', 0)
            ->count();
        $partialCount = $bills->where('paid_amount', '>', 0)
            ->where('remaining_balance', '>', 0)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_outstanding' => $totalOutstanding,
                'overdue_amount' => $overdueAmount,
                'pending_amount' => $pendingAmount,
                'partial_amount' => $partialAmount,
                'overdue_count' => $overdueCount,
                'pending_count' => $pendingCount,
                'partial_count' => $partialCount,
                'total_bills' => $bills->count()
            ]
        ]);
    }

    /**
     * Get payment history for a specific bill
     */
    public function getPaymentHistory($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::with(['payments'])->find($billId);

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'Bill not found'
            ], 404);
        }

        // Check if user can access this bill
        if ($bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this bill'
            ], 403);
        }

        $payments = $bill->payments->map(function($payment) {
            return [
                'id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date
            ];
        });

        $totalPayments = $payments->count();
        $completedPayments = $payments->where('status', 'completed')->count();
        $totalPaid = $payments->where('status', 'completed')->sum('amount');
        $paymentProgress = $bill->total_amount > 0 ? round(($totalPaid / $bill->total_amount) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'bill' => [
                    'id' => $bill->id,
                    'bill_number' => $bill->bill_number,
                    'total_amount' => $bill->total_amount,
                    'paid_amount' => $bill->paid_amount,
                    'remaining_balance' => $bill->remaining_balance,
                    'status' => $bill->status
                ],
                'payments' => $payments,
                'payment_summary' => [
                    'total_payments' => $totalPayments,
                    'completed_payments' => $completedPayments,
                    'total_paid' => $totalPaid,
                    'payment_progress' => $paymentProgress
                ]
            ]
        ]);
    }

    /**
     * Get bill receipt URL
     */
    public function getReceipt($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::find($billId);

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'Bill not found'
            ], 404);
        }

        // Check if user can access this bill
        if ($bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this bill'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_url' => route('staff.bill.print', $bill->id),
                'bill_number' => $bill->bill_number,
                'download_message' => 'Receipt generated successfully'
            ]
        ]);
    }
}