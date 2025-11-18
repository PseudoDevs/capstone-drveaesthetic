<?php

namespace App\Filament\Staff\Resources\PaymentResource\Pages;

use App\Filament\Staff\Resources\PaymentResource;
use App\Models\Bill;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-fill payment number if not set
        if (empty($data['payment_number'])) {
            $data['payment_number'] = \App\Models\Payment::generatePaymentNumber();
        }

        // Set status to completed by default if not set
        if (empty($data['status'])) {
            $data['status'] = 'completed';
        }

        // Ensure client_id is set from bill if not already set
        if (empty($data['client_id']) && !empty($data['bill_id'])) {
            $bill = Bill::find($data['bill_id']);
            if ($bill) {
                $data['client_id'] = $bill->client_id;
            }
        }

        // Ensure appointment_id is set from bill if not already set
        if (empty($data['appointment_id']) && !empty($data['bill_id'])) {
            $bill = Bill::find($data['bill_id']);
            if ($bill) {
                $data['appointment_id'] = $bill->appointment_id;
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Update the bill balance after payment is created
        $payment = $this->record;
        
        if ($payment->bill) {
            $payment->bill->updateBalance();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Check if bill_id is passed in the URL
        $billId = request()->get('bill_id');
        
        if ($billId) {
            $bill = Bill::with(['client', 'appointment.service'])->find($billId);
            
            if ($bill) {
                // Auto-fill form data based on the bill
                $data['bill_id'] = $bill->id;
                $data['client_id'] = $bill->client_id;
                $data['appointment_id'] = $bill->appointment_id;
                $data['received_by'] = auth()->id();
                $data['payment_date'] = now()->format('Y-m-d');
                
                // Calculate the expected payment amount
                if ($bill->payment_type === 'staggered') {
                    if (!$bill->isDownPaymentMade()) {
                        // First payment should be down payment
                        $data['amount'] = $bill->down_payment;
                    } else {
                        // Subsequent payments should be installment amount
                        $data['amount'] = $bill->getNextPaymentAmount();
                    }
                } else {
                    // Full payment - remaining balance
                    $data['amount'] = $bill->remaining_balance;
                }
                
                // Auto-fill payment method
                $data['payment_method'] = 'cash';
                
                // Auto-fill payment reference based on payment type
                if ($bill->payment_type === 'staggered') {
                    if (!$bill->isDownPaymentMade()) {
                        $data['payment_reference'] = 'Down Payment - ' . $bill->bill_number;
                    } else {
                        $installmentNum = $bill->getNextInstallmentNumber() - 1;
                        $data['payment_reference'] = "Installment #{$installmentNum} - " . $bill->bill_number;
                    }
                } else {
                    $data['payment_reference'] = 'Full Payment - ' . $bill->bill_number;
                }
            }
        }

        return $data;
    }
}
