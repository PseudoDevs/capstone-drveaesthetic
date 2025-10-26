<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    /**
     * Get billing dashboard data
     */
    public function dashboard(): JsonResponse
    {
        try {
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

            // Get recent payments
            $recentPayments = Payment::whereHas('bill', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->with(['bill.appointment.service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

            // Get staggered bills
            $staggeredBills = $bills->where('payment_type', 'staggered');
            $activeInstallments = $staggeredBills->where('remaining_balance', '>', 0);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'statistics' => [
                        'total_bills' => $totalBills,
                        'total_amount' => $totalAmount,
                        'paid_amount' => $paidAmount,
                        'remaining_balance' => $remainingBalance,
                        'payment_progress' => round($paymentProgress, 2),
                    ],
                    'bills_by_status' => [
                        'paid' => $paidBills->count(),
                        'unpaid' => $unpaidBills->count(),
                        'partial' => $partialBills->count(),
                        'overdue' => $overdueBills->count(),
                    ],
                    'recent_payments' => $recentPayments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'payment_method' => $payment->payment_method,
                            'payment_date' => $payment->payment_date,
                            'status' => $payment->status,
                            'service_name' => $payment->bill->appointment->service->service_name ?? 'N/A',
                        ];
                    }),
                    'staggered_payments' => [
                        'total_staggered' => $staggeredBills->count(),
                        'active_installments' => $activeInstallments->count(),
                        'installments' => $activeInstallments->map(function($bill) {
                            return [
                                'id' => $bill->id,
                                'service_name' => $bill->appointment->service->service_name ?? 'N/A',
                                'total_amount' => $bill->total_amount,
                                'paid_amount' => $bill->paid_amount,
                                'remaining_balance' => $bill->remaining_balance,
                                'due_date' => $bill->due_date,
                                'payment_type' => $bill->payment_type,
                            ];
                        }),
                    ],
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching billing data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment history
     */
    public function paymentHistory(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 20);
            $page = $request->get('page', 1);

            $payments = Payment::whereHas('bill', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->with(['bill.appointment.service'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $payments->items(),
                    'pagination' => [
                        'current_page' => $payments->currentPage(),
                        'last_page' => $payments->lastPage(),
                        'per_page' => $payments->perPage(),
                        'total' => $payments->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get outstanding balance
     */
    public function outstandingBalance(): JsonResponse
    {
        try {
            $user = Auth::user();

            $bills = Bill::where('client_id', $user->id)
                ->where('remaining_balance', '>', 0)
                ->with(['appointment.service'])
                ->orderBy('due_date', 'asc')
                ->get();

            $totalOutstanding = $bills->sum('remaining_balance');
            $overdueBills = $bills->where('due_date', '<', now());

            return response()->json([
                'success' => true,
                'data' => [
                    'total_outstanding' => $totalOutstanding,
                    'overdue_count' => $overdueBills->count(),
                    'bills' => $bills->map(function($bill) {
                        return [
                            'id' => $bill->id,
                            'service_name' => $bill->appointment->service->service_name ?? 'N/A',
                            'total_amount' => $bill->total_amount,
                            'paid_amount' => $bill->paid_amount,
                            'remaining_balance' => $bill->remaining_balance,
                            'due_date' => $bill->due_date,
                            'is_overdue' => $bill->due_date < now(),
                            'payment_type' => $bill->payment_type,
                        ];
                    }),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching outstanding balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request): JsonResponse
    {
        try {
            $validator = $request->validate([
                'bill_id' => 'required|exists:bills,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string|in:cash,card,bank_transfer,gcash,paymaya',
                'payment_date' => 'required|date|before_or_equal:today',
            ]);

            $user = Auth::user();
            $bill = Bill::where('id', $validator['bill_id'])
                ->where('client_id', $user->id)
                ->first();

            if (!$bill) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bill not found or access denied'
                ], 404);
            }

            if ($validator['amount'] > $bill->remaining_balance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount cannot exceed remaining balance'
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'bill_id' => $bill->id,
                'amount' => $validator['amount'],
                'payment_method' => $validator['payment_method'],
                'payment_date' => $validator['payment_date'],
                'status' => 'completed',
                'notes' => 'Mobile app payment',
            ]);

            // Update bill
            $bill->update([
                'paid_amount' => $bill->paid_amount + $validator['amount'],
                'remaining_balance' => $bill->remaining_balance - $validator['amount'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'payment' => $payment,
                    'bill' => $bill->fresh(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

