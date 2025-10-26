<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentApiController extends Controller
{
    /**
     * Get all payments for a specific client
     */
    public function getClientPayments($clientId): JsonResponse
    {
        // Verify the authenticated user can access this client's payments
        $user = Auth::user();
        if ($user->id != $clientId && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client payments'
            ], 403);
        }

        $payments = Payment::whereHas('bill', function($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })
        ->with(['bill.appointment.service', 'receivedBy'])
        ->orderBy('payment_date', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'bill_id' => $payment->bill_id,
                    'client_id' => $payment->client_id,
                    'appointment_id' => $payment->appointment_id,
                    'received_by' => $payment->received_by,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_reference' => $payment->payment_reference,
                    'notes' => $payment->notes,
                    'status' => $payment->status,
                    'payment_date' => $payment->payment_date?->format('Y-m-d'),
                    'processed_at' => $payment->processed_at?->toISOString(),
                    'bank_name' => $payment->bank_name,
                    'check_number' => $payment->check_number,
                    'check_date' => $payment->check_date?->format('Y-m-d'),
                    'transaction_details' => $payment->transaction_details,
                    'is_completed' => $payment->isCompleted(),
                    'created_at' => $payment->created_at?->toISOString(),
                    'updated_at' => $payment->updated_at?->toISOString(),
                    'bill' => $payment->bill ? [
                        'id' => $payment->bill->id,
                        'bill_number' => $payment->bill->bill_number,
                        'total_amount' => $payment->bill->total_amount,
                        'remaining_balance' => $payment->bill->remaining_balance,
                        'status' => $payment->bill->status,
                        'appointment' => $payment->bill->appointment ? [
                            'id' => $payment->bill->appointment->id,
                            'appointment_date' => $payment->bill->appointment->appointment_date?->format('Y-m-d'),
                            'service' => $payment->bill->appointment->service ? [
                                'id' => $payment->bill->appointment->service->id,
                                'service_name' => $payment->bill->appointment->service->service_name,
                            ] : null,
                        ] : null,
                    ] : null,
                    'received_by_user' => $payment->receivedBy ? [
                        'id' => $payment->receivedBy->id,
                        'name' => $payment->receivedBy->name,
                        'role' => $payment->receivedBy->role,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Get a specific payment by ID
     */
    public function getPayment($paymentId): JsonResponse
    {
        $user = Auth::user();
        
        $payment = Payment::with(['bill.appointment.service', 'receivedBy', 'client'])
            ->findOrFail($paymentId);

        // Verify the authenticated user can access this payment
        if ($user->id != $payment->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to payment'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'bill_id' => $payment->bill_id,
                'client_id' => $payment->client_id,
                'appointment_id' => $payment->appointment_id,
                'received_by' => $payment->received_by,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_reference' => $payment->payment_reference,
                'notes' => $payment->notes,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date?->format('Y-m-d'),
                'processed_at' => $payment->processed_at?->toISOString(),
                'bank_name' => $payment->bank_name,
                'check_number' => $payment->check_number,
                'check_date' => $payment->check_date?->format('Y-m-d'),
                'transaction_details' => $payment->transaction_details,
                'is_completed' => $payment->isCompleted(),
                'created_at' => $payment->created_at?->toISOString(),
                'updated_at' => $payment->updated_at?->toISOString(),
                'bill' => $payment->bill ? [
                    'id' => $payment->bill->id,
                    'bill_number' => $payment->bill->bill_number,
                    'total_amount' => $payment->bill->total_amount,
                    'paid_amount' => $payment->bill->paid_amount,
                    'remaining_balance' => $payment->bill->remaining_balance,
                    'status' => $payment->bill->status,
                    'appointment' => $payment->bill->appointment ? [
                        'id' => $payment->bill->appointment->id,
                        'appointment_date' => $payment->bill->appointment->appointment_date?->format('Y-m-d'),
                        'appointment_time' => $payment->bill->appointment->appointment_time,
                        'service' => $payment->bill->appointment->service ? [
                            'id' => $payment->bill->appointment->service->id,
                            'service_name' => $payment->bill->appointment->service->service_name,
                            'price' => $payment->bill->appointment->service->price,
                        ] : null,
                    ] : null,
                ] : null,
                'client' => $payment->client ? [
                    'id' => $payment->client->id,
                    'name' => $payment->client->name,
                    'email' => $payment->client->email,
                    'phone' => $payment->client->phone,
                ] : null,
                'received_by_user' => $payment->receivedBy ? [
                    'id' => $payment->receivedBy->id,
                    'name' => $payment->receivedBy->name,
                    'role' => $payment->receivedBy->role,
                ] : null,
            ]
        ]);
    }

    /**
     * Process a new payment
     */
    public function processPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,credit_card,debit_card,bank_transfer,check,gcash,maya,paymaya',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'bank_name' => 'nullable|string|max:255',
            'check_number' => 'nullable|string|max:255',
            'check_date' => 'nullable|date',
            'transaction_details' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        // Get the bill and verify access
        $bill = Bill::findOrFail($validated['bill_id']);
        
        // Verify the authenticated user can make payments for this bill
        if ($user->id != $bill->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to make payments for this bill'
            ], 403);
        }

        // Check if payment amount is valid
        if ($validated['amount'] > $bill->remaining_balance) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount cannot exceed remaining balance'
            ], 400);
        }

        // Check if bill is already fully paid
        if ($bill->isFullyPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'This bill is already fully paid'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create the payment
            $payment = Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'bill_id' => $validated['bill_id'],
                'client_id' => $bill->client_id,
                'appointment_id' => $bill->appointment_id,
                'received_by' => $user->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'],
                'notes' => $validated['notes'],
                'status' => 'completed', // Auto-complete for now
                'payment_date' => now()->toDateString(),
                'processed_at' => now(),
                'bank_name' => $validated['bank_name'],
                'check_number' => $validated['check_number'],
                'check_date' => $validated['check_date'],
                'transaction_details' => $validated['transaction_details'],
            ]);

            // Update bill balance
            $bill->updateBalance();

            DB::commit();

            // Load relationships for response
            $payment->load(['bill.appointment.service', 'receivedBy']);

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
                    'payment_date' => $payment->payment_date?->format('Y-m-d'),
                    'processed_at' => $payment->processed_at?->toISOString(),
                    'notes' => $payment->notes,
                    'bill' => [
                        'id' => $payment->bill->id,
                        'bill_number' => $payment->bill->bill_number,
                        'total_amount' => $payment->bill->total_amount,
                        'paid_amount' => $payment->bill->paid_amount,
                        'remaining_balance' => $payment->bill->remaining_balance,
                        'status' => $payment->bill->status,
                        'is_fully_paid' => $payment->bill->isFullyPaid(),
                    ],
                    'received_by' => $payment->receivedBy ? [
                        'id' => $payment->receivedBy->id,
                        'name' => $payment->receivedBy->name,
                    ] : null,
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
        // Verify the authenticated user can access this client's payment summary
        $user = Auth::user();
        if ($user->id != $clientId && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client payment summary'
            ], 403);
        }

        $payments = Payment::whereHas('bill', function($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })->get();

        $totalPaid = $payments->where('status', 'completed')->sum('amount');
        $completedPayments = $payments->where('status', 'completed')->count();
        $pendingPayments = $payments->where('status', 'pending')->count();
        $failedPayments = $payments->where('status', 'failed')->count();

        // Get payment methods breakdown
        $paymentMethods = $payments->where('status', 'completed')
            ->groupBy('payment_method')
            ->map(function($group) {
                return [
                    'method' => $group->first()->payment_method,
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount'),
                ];
            })->values();

        // Get recent payments (last 5)
        $recentPayments = $payments->where('status', 'completed')
            ->sortByDesc('payment_date')
            ->take(5)
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_date' => $payment->payment_date?->format('Y-m-d'),
                    'bill_number' => $payment->bill->bill_number ?? 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_paid' => $totalPaid,
                'total_payments' => $payments->count(),
                'completed_payments' => $completedPayments,
                'pending_payments' => $pendingPayments,
                'failed_payments' => $failedPayments,
                'payment_methods' => $paymentMethods,
                'recent_payments' => $recentPayments,
                'average_payment' => $completedPayments > 0 ? round($totalPaid / $completedPayments, 2) : 0,
            ]
        ]);
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt($paymentId): JsonResponse
    {
        $user = Auth::user();
        
        $payment = Payment::findOrFail($paymentId);

        // Verify the authenticated user can access this payment receipt
        if ($user->id != $payment->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to payment receipt'
            ], 403);
        }

        // Generate the PDF URL (using existing PaymentController logic)
        $receiptUrl = route('payments.print', $payment);

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_url' => $receiptUrl,
                'payment_number' => $payment->payment_number,
                'download_message' => 'Payment receipt generated successfully'
            ]
        ]);
    }
}
