<?php

namespace App\Console\Commands;

use App\Models\Bill;
use Illuminate\Console\Command;

class RecalculateBillBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:recalculate-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all bill balances based on completed payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating bill balances...');
        
        $bills = Bill::with('payments')->get();
        $updated = 0;
        
        foreach ($bills as $bill) {
            $oldPaidAmount = $bill->paid_amount;
            $oldRemainingBalance = $bill->remaining_balance;
            
            // Recalculate balance
            $bill->updateBalance();
            
            if ($oldPaidAmount != $bill->paid_amount || $oldRemainingBalance != $bill->remaining_balance) {
                $updated++;
                $this->line("Updated Bill #{$bill->bill_number}: Paid: {$oldPaidAmount} → {$bill->paid_amount}, Balance: {$oldRemainingBalance} → {$bill->remaining_balance}");
            }
        }
        
        $this->info("✓ Recalculated {$updated} bills out of {$bills->count()} total bills.");
        
        return Command::SUCCESS;
    }
}
