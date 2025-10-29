<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentApiController extends Controller
{
    /**
     * Get all payments for a specific client
     */
    public function getUserPayments($clientId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user can access this client's payments
        if ($user->id != $clientId && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client payments'
            ], 403);
        }

        $payments = Payment::whereHas('bill', function($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })
        ->with(['bill.appointment.service', 'client'])
        ->orderBy('created_at', 'desc')
        ->get();

        $paymentsData = $payments->map(function($payment) {
            return [
                'id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'bill_id' => $payment->bill_id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date,
                'is_completed' => $payment->status === 'completed',
                'bill' => [
                    'id' => $payment->bill->id,
                    'bill_number' => $payment->bill->bill_number,
                    'total_amount' => $payment->bill->total_amount,
                    'remaining_balance' => $payment->bill->remaining_balance,
                    'status' => $payment->bill->status
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $paymentsData
        ]);
    }

    /**
     * Get specific payment details
     */
    public function show($paymentId): JsonResponse
    {
        $user = Auth::user();
        
        $payment = Payment::with(['bill.appointment.service', 'client'])->find($paymentId);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        // Check if user can access this payment
        if ($payment->bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this payment'
            ], 403);
        }

        $paymentData = [
            'id' => $payment->id,
            'payment_number' => $payment->payment_number,
            'bill_id' => $payment->bill_id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_reference' => $payment->payment_reference,
            'status' => $payment->status,
            'payment_date' => $payment->payment_date,
            'notes' => $payment->notes,
            'bill' => [
                'id' => $payment->bill->id,
                'bill_number' => $payment->bill->bill_number,
                'total_amount' => $payment->bill->total_amount,
                'paid_amount' => $payment->bill->paid_amount,
                'remaining_balance' => $payment->bill->remaining_balance,
                'status' => $payment->bill->status
            ],
            'client' => [
                'id' => $payment->client->id,
                'name' => $payment->client->name,
                'email' => $payment->client->email
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $paymentData
        ]);
    }

    /**
     * Process a new payment
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,credit_card,debit_card,bank_transfer,check,gcash,maya,paymaya',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $bill = Bill::find($validated['bill_id']);

        // Check if user can make payment for this bill
        if ($bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to make payment for this bill'
            ], 403);
        }

        // Check if bill is already fully paid
        if ($bill->remaining_balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This bill is already fully paid'
            ], 400);
        }

        // Check if payment amount exceeds remaining balance
        if ($validated['amount'] > $bill->remaining_balance) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount cannot exceed remaining balance'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Generate payment number
            $paymentNumber = 'PAY-' . now()->format('Y') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create payment
            $payment = Payment::create([
                'payment_number' => $paymentNumber,
                'bill_id' => $bill->id,
                'client_id' => $bill->client_id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'],
                'status' => 'completed', // For now, auto-complete. In production, integrate with payment gateway
                'payment_date' => now()->format('Y-m-d'),
                'notes' => $validated['notes']
            ]);

            // Update bill
            $bill->paid_amount += $validated['amount'];
            $bill->remaining_balance = $bill->total_amount - $bill->paid_amount;
            
            // Update bill status
            if ($bill->remaining_balance <= 0) {
                $bill->status = 'paid';
            } elseif ($bill->paid_amount > 0) {
                $bill->status = 'partial';
            }
            
            $bill->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'bill_id' => $payment->bill_id,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_reference' => $payment->payment_reference,
                    'status' => $payment->status,
                    'payment_date' => $payment->payment_date,
                    'bill' => [
                        'id' => $bill->id,
                        'bill_number' => $bill->bill_number,
                        'total_amount' => $bill->total_amount,
                        'paid_amount' => $bill->paid_amount,
                        'remaining_balance' => $bill->remaining_balance,
                        'status' => $bill->status,
                        'is_fully_paid' => $bill->remaining_balance <= 0
                    ]
                ],
                'message' => 'Payment processed successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment summary for a client
     */
    public function getPaymentSummary($clientId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user can access this client's data
        if ($user->id != $clientId && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client data'
            ], 403);
        }

        $payments = Payment::whereHas('bill', function($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })->get();

        $totalPaid = $payments->where('status', 'completed')->sum('amount');
        $totalPayments = $payments->count();
        $completedPayments = $payments->where('status', 'completed')->count();
        $pendingPayments = $payments->where('status', 'pending')->count();
        $failedPayments = $payments->where('status', 'failed')->count();
        $averagePayment = $completedPayments > 0 ? $totalPaid / $completedPayments : 0;

        // Group by payment method
        $paymentMethods = $payments->where('status', 'completed')
            ->groupBy('payment_method')
            ->map(function($group) {
                return [
                    'method' => $group->first()->payment_method,
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount')
                ];
            })->values();

        // Recent payments (last 5)
        $recentPayments = $payments->where('status', 'completed')
            ->sortByDesc('created_at')
            ->take(5)
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_date' => $payment->payment_date,
                    'bill_number' => $payment->bill->bill_number
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_paid' => $totalPaid,
                'total_payments' => $totalPayments,
                'completed_payments' => $completedPayments,
                'pending_payments' => $pendingPayments,
                'failed_payments' => $failedPayments,
                'average_payment' => $averagePayment,
                'payment_methods' => $paymentMethods,
                'recent_payments' => $recentPayments
            ]
        ]);
    }

    /**
     * Get payment receipt URL
     */
    public function getReceipt($paymentId): JsonResponse
    {
        $user = Auth::user();
        
        $payment = Payment::with('bill')->find($paymentId);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        // Check if user can access this payment
        if ($payment->bill->client_id != $user->id && !in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this payment'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_url' => route('staff.payment.print', $payment->id),
                'payment_number' => $payment->payment_number,
                'download_message' => 'Payment receipt generated successfully'
            ]
        ]);
    }
}