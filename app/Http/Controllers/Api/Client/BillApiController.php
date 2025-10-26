<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BillApiController extends Controller
{
    /**
     * Get all bills for a specific client
     */
    public function getClientBills($clientId): JsonResponse
    {
        // Verify the authenticated user can access this client's bills
        $user = Auth::user();
        if ($user->id != $clientId && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client bills'
            ], 403);
        }

        $bills = Bill::where('client_id', $clientId)
            ->with(['appointment.service', 'payments', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bills->map(function($bill) {
                return [
                    'id' => $bill->id,
                    'bill_number' => $bill->bill_number,
                    'client_id' => $bill->client_id,
                    'appointment_id' => $bill->appointment_id,
                    'bill_type' => $bill->bill_type,
                    'payment_type' => $bill->payment_type,
                    'total_installments' => $bill->total_installments,
                    'down_payment' => $bill->down_payment,
                    'installment_amount' => $bill->installment_amount,
                    'description' => $bill->description,
                    'subtotal' => $bill->subtotal,
                    'tax_amount' => $bill->tax_amount,
                    'discount_amount' => $bill->discount_amount,
                    'total_amount' => $bill->total_amount,
                    'paid_amount' => $bill->paid_amount,
                    'remaining_balance' => $bill->remaining_balance,
                    'status' => $bill->status,
                    'bill_date' => $bill->bill_date?->format('Y-m-d'),
                    'due_date' => $bill->due_date?->format('Y-m-d'),
                    'paid_date' => $bill->paid_date?->format('Y-m-d'),
                    'notes' => $bill->notes,
                    'is_overdue' => $bill->isOverdue(),
                    'payment_progress' => $bill->payment_progress,
                    'days_until_due' => $bill->days_until_due,
                    'is_fully_paid' => $bill->isFullyPaid(),
                    'is_staggered_payment' => $bill->isStaggeredPayment(),
                    'next_payment_amount' => $bill->getNextPaymentAmount(),
                    'created_at' => $bill->created_at?->toISOString(),
                    'updated_at' => $bill->updated_at?->toISOString(),
                    'appointment' => $bill->appointment ? [
                        'id' => $bill->appointment->id,
                        'appointment_date' => $bill->appointment->appointment_date?->format('Y-m-d'),
                        'appointment_time' => $bill->appointment->appointment_time,
                        'status' => $bill->appointment->status,
                        'service' => $bill->appointment->service ? [
                            'id' => $bill->appointment->service->id,
                            'service_name' => $bill->appointment->service->service_name,
                            'price' => $bill->appointment->service->price,
                        ] : null,
                    ] : null,
                    'payments' => $bill->payments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'payment_number' => $payment->payment_number,
                            'amount' => $payment->amount,
                            'payment_method' => $payment->payment_method,
                            'payment_reference' => $payment->payment_reference,
                            'status' => $payment->status,
                            'payment_date' => $payment->payment_date?->format('Y-m-d'),
                            'processed_at' => $payment->processed_at?->toISOString(),
                        ];
                    }),
                ];
            })
        ]);
    }

    /**
     * Get a specific bill by ID
     */
    public function getBill($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::with(['appointment.service', 'payments', 'createdBy', 'client'])
            ->findOrFail($billId);

        // Verify the authenticated user can access this bill
        if ($user->id != $bill->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to bill'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $bill->id,
                'bill_number' => $bill->bill_number,
                'client_id' => $bill->client_id,
                'appointment_id' => $bill->appointment_id,
                'bill_type' => $bill->bill_type,
                'payment_type' => $bill->payment_type,
                'total_installments' => $bill->total_installments,
                'down_payment' => $bill->down_payment,
                'installment_amount' => $bill->installment_amount,
                'description' => $bill->description,
                'subtotal' => $bill->subtotal,
                'tax_amount' => $bill->tax_amount,
                'discount_amount' => $bill->discount_amount,
                'total_amount' => $bill->total_amount,
                'paid_amount' => $bill->paid_amount,
                'remaining_balance' => $bill->remaining_balance,
                'status' => $bill->status,
                'bill_date' => $bill->bill_date?->format('Y-m-d'),
                'due_date' => $bill->due_date?->format('Y-m-d'),
                'paid_date' => $bill->paid_date?->format('Y-m-d'),
                'notes' => $bill->notes,
                'terms_conditions' => $bill->terms_conditions,
                'is_overdue' => $bill->isOverdue(),
                'payment_progress' => $bill->payment_progress,
                'days_until_due' => $bill->days_until_due,
                'is_fully_paid' => $bill->isFullyPaid(),
                'is_staggered_payment' => $bill->isStaggeredPayment(),
                'next_payment_amount' => $bill->getNextPaymentAmount(),
                'is_down_payment_made' => $bill->isDownPaymentMade(),
                'next_installment_number' => $bill->getNextInstallmentNumber(),
                'created_at' => $bill->created_at?->toISOString(),
                'updated_at' => $bill->updated_at?->toISOString(),
                'appointment' => $bill->appointment ? [
                    'id' => $bill->appointment->id,
                    'appointment_date' => $bill->appointment->appointment_date?->format('Y-m-d'),
                    'appointment_time' => $bill->appointment->appointment_time,
                    'status' => $bill->appointment->status,
                    'service' => $bill->appointment->service ? [
                        'id' => $bill->appointment->service->id,
                        'service_name' => $bill->appointment->service->service_name,
                        'price' => $bill->appointment->service->price,
                        'description' => $bill->appointment->service->description,
                    ] : null,
                ] : null,
                'client' => $bill->client ? [
                    'id' => $bill->client->id,
                    'name' => $bill->client->name,
                    'email' => $bill->client->email,
                    'phone' => $bill->client->phone,
                ] : null,
                'created_by' => $bill->createdBy ? [
                    'id' => $bill->createdBy->id,
                    'name' => $bill->createdBy->name,
                    'role' => $bill->createdBy->role,
                ] : null,
                'payments' => $bill->payments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'payment_reference' => $payment->payment_reference,
                        'status' => $payment->status,
                        'payment_date' => $payment->payment_date?->format('Y-m-d'),
                        'processed_at' => $payment->processed_at?->toISOString(),
                        'notes' => $payment->notes,
                        'received_by' => $payment->receivedBy ? [
                            'id' => $payment->receivedBy->id,
                            'name' => $payment->receivedBy->name,
                        ] : null,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get outstanding balance for a client
     */
    public function getOutstandingBalance($clientId): JsonResponse
    {
        // Verify the authenticated user can access this client's balance
        $user = Auth::user();
        if ($user->id != $clientId && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to client balance'
            ], 403);
        }

        $bills = Bill::where('client_id', $clientId)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->get();

        $totalOutstanding = $bills->sum('remaining_balance');
        $overdueAmount = $bills->where('status', 'overdue')->sum('remaining_balance');
        $pendingAmount = $bills->where('status', 'pending')->sum('remaining_balance');
        $partialAmount = $bills->where('status', 'partial')->sum('remaining_balance');

        return response()->json([
            'success' => true,
            'data' => [
                'total_outstanding' => $totalOutstanding,
                'overdue_amount' => $overdueAmount,
                'pending_amount' => $pendingAmount,
                'partial_amount' => $partialAmount,
                'overdue_count' => $bills->where('status', 'overdue')->count(),
                'pending_count' => $bills->where('status', 'pending')->count(),
                'partial_count' => $bills->where('status', 'partial')->count(),
                'total_bills' => $bills->count(),
            ]
        ]);
    }

    /**
     * Download bill receipt/PDF
     */
    public function downloadReceipt($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::findOrFail($billId);

        // Verify the authenticated user can access this bill
        if ($user->id != $bill->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to bill receipt'
            ], 403);
        }

        // Generate the PDF URL (using existing BillController logic)
        $receiptUrl = route('bills.print', $bill);

        return response()->json([
            'success' => true,
            'data' => [
                'receipt_url' => $receiptUrl,
                'bill_number' => $bill->bill_number,
                'download_message' => 'Receipt generated successfully'
            ]
        ]);
    }

    /**
     * Get bill payment history
     */
    public function getBillPaymentHistory($billId): JsonResponse
    {
        $user = Auth::user();
        
        $bill = Bill::with(['payments.receivedBy'])->findOrFail($billId);

        // Verify the authenticated user can access this bill
        if ($user->id != $bill->client_id && !in_array($user->role, ['Admin', 'Staff', 'Doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to bill payment history'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bill' => [
                    'id' => $bill->id,
                    'bill_number' => $bill->bill_number,
                    'total_amount' => $bill->total_amount,
                    'paid_amount' => $bill->paid_amount,
                    'remaining_balance' => $bill->remaining_balance,
                    'status' => $bill->status,
                ],
                'payments' => $bill->payments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'payment_reference' => $payment->payment_reference,
                        'status' => $payment->status,
                        'payment_date' => $payment->payment_date?->format('Y-m-d'),
                        'processed_at' => $payment->processed_at?->toISOString(),
                        'notes' => $payment->notes,
                        'received_by' => $payment->receivedBy ? [
                            'id' => $payment->receivedBy->id,
                            'name' => $payment->receivedBy->name,
                        ] : null,
                    ];
                }),
                'payment_summary' => [
                    'total_payments' => $bill->payments->count(),
                    'completed_payments' => $bill->payments->where('status', 'completed')->count(),
                    'total_paid' => $bill->payments->where('status', 'completed')->sum('amount'),
                    'payment_progress' => $bill->payment_progress,
                ]
            ]
        ]);
    }
}
