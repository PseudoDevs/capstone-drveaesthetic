<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Console\Command;

class DiagnosePayments extends Command
{
    protected $signature = 'payments:diagnose {user_id?}';

    protected $description = 'Diagnose payment visibility issues for a specific user or all users';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $this->diagnoseUser($userId);
        } else {
            $this->diagnoseAll();
        }
        
        return Command::SUCCESS;
    }
    
    private function diagnoseUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }
        
        $this->info("=== Diagnosing Payments for User: {$user->name} (ID: {$user->id}) ===\n");
        
        // Get bills for this user
        $bills = Bill::where('client_id', $user->id)->get();
        $this->info("Bills for this user: {$bills->count()}");
        foreach ($bills as $bill) {
            $this->line("  - Bill #{$bill->bill_number}: {$bill->payments()->count()} payments");
        }
        
        // Get payments with direct client_id match
        $paymentsByClientId = Payment::where('client_id', $user->id)->get();
        $this->info("\nPayments with client_id = {$user->id}: {$paymentsByClientId->count()}");
        foreach ($paymentsByClientId as $payment) {
            $this->line("  - Payment #{$payment->payment_number}: Amount ₱{$payment->amount}, Bill: " . ($payment->bill ? $payment->bill->bill_number : 'N/A'));
        }
        
        // Get payments through bills
        $paymentsByBill = Payment::whereHas('bill', function ($q) use ($user) {
            $q->where('client_id', $user->id);
        })->get();
        $this->info("\nPayments through bills (bill.client_id = {$user->id}): {$paymentsByBill->count()}");
        foreach ($paymentsByBill as $payment) {
            $this->line("  - Payment #{$payment->payment_number}: client_id = " . ($payment->client_id ?? 'NULL') . ", Bill: " . ($payment->bill ? $payment->bill->bill_number : 'N/A'));
        }
        
        // Payments that should show but don't
        $allPaymentsForBills = Payment::whereIn('bill_id', $bills->pluck('id'))->get();
        $this->info("\nAll payments for this user's bills: {$allPaymentsForBills->count()}");
        foreach ($allPaymentsForBills as $payment) {
            $clientIdStatus = $payment->client_id == $user->id ? '✓' : ($payment->client_id ? '✗ (different)' : '✗ (NULL)');
            $this->line("  - Payment #{$payment->payment_number}: client_id {$clientIdStatus}, Bill client_id: " . ($payment->bill ? $payment->bill->client_id : 'N/A'));
        }
    }
    
    private function diagnoseAll()
    {
        $this->info("=== Diagnosing All Payment Issues ===\n");
        
        // Payments with NULL client_id
        $nullClientPayments = Payment::whereNull('client_id')->with('bill')->get();
        $this->info("Payments with NULL client_id: {$nullClientPayments->count()}");
        foreach ($nullClientPayments as $payment) {
            $billClientId = $payment->bill ? $payment->bill->client_id : 'N/A';
            $this->line("  - Payment #{$payment->payment_number}: Bill client_id = {$billClientId}");
        }
        
        // Payments where client_id doesn't match bill.client_id
        $mismatchedPayments = Payment::with('bill')->get()->filter(function($payment) {
            return $payment->bill && $payment->client_id && $payment->client_id != $payment->bill->client_id;
        });
        $this->info("\nPayments where client_id ≠ bill.client_id: {$mismatchedPayments->count()}");
        foreach ($mismatchedPayments as $payment) {
            $this->line("  - Payment #{$payment->payment_number}: client_id = {$payment->client_id}, Bill client_id = {$payment->bill->client_id}");
        }
        
        // Summary by user
        $this->info("\n=== Summary by User ===");
        $userIds = Bill::distinct()->pluck('client_id')->merge(Payment::distinct()->pluck('client_id'))->filter()->unique();
        $users = User::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            $billCount = Bill::where('client_id', $user->id)->count();
            $paymentCount = Payment::where(function($q) use ($user) {
                $q->where('client_id', $user->id)
                  ->orWhereHas('bill', fn($q2) => $q2->where('client_id', $user->id));
            })->count();
            $this->line("User {$user->name} (ID: {$user->id}): {$billCount} bills, {$paymentCount} visible payments");
        }
    }
}

