<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fix-client-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix payments that are missing client_id by getting it from their bill';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing payments with missing client_id...');
        
        $payments = Payment::whereNull('client_id')
            ->orWhere('client_id', 0)
            ->with('bill')
            ->get();
        
        $fixed = 0;
        
        foreach ($payments as $payment) {
            if ($payment->bill && $payment->bill->client_id) {
                $payment->client_id = $payment->bill->client_id;
                
                // Also fix appointment_id if missing
                if (empty($payment->appointment_id) && $payment->bill->appointment_id) {
                    $payment->appointment_id = $payment->bill->appointment_id;
                }
                
                $payment->save();
                $fixed++;
                $this->line("Fixed Payment #{$payment->payment_number}: Set client_id to {$payment->bill->client_id}");
            } else {
                $this->warn("Payment #{$payment->payment_number} has no bill or bill has no client_id");
            }
        }
        
        $this->info("✓ Fixed {$fixed} payments out of {$payments->count()} payments with missing client_id.");
        
        return Command::SUCCESS;
    }
}
