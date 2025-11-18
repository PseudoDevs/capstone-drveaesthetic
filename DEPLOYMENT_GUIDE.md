# Deployment Guide: Payment and Billing Fixes

This guide will help you manually deploy the changes made to fix payment visibility and billing balance issues.

## Changes Summary

1. **Fixed dark mode visibility** in payment balance modal
2. **Fixed payment creation/editing** to automatically update bill balances
3. **Improved payment query** to show payments correctly for all clients
4. **Fixed negative balance issue** by capping payments at bill total

---

## Step 1: Backup Current Files (IMPORTANT!)

Before making any changes, backup these files on your hosted server:
- `app/Models/Bill.php`
- `app/Filament/Staff/Resources/PaymentResource/Pages/CreatePayment.php`
- `app/Filament/Staff/Resources/PaymentResource/Pages/EditPayment.php`
- `app/Http/Controllers/DashboardController.php`
- `resources/views/filament/staff/modals/balance-details.blade.php`

---

## Step 2: Update Files

### File 1: `app/Models/Bill.php`

**Location:** Find the `updateBalance()` method (around line 86-105)

**Replace this:**
```php
public function updateBalance(): void
{
    $this->paid_amount = $this->payments()->where('status', 'completed')->sum('amount');
    $this->remaining_balance = $this->total_amount - $this->paid_amount;
    
    // Update status based on balance
    if ($this->remaining_balance <= 0) {
        $this->status = 'paid';
        $this->paid_date = now()->toDateString();
    } elseif ($this->paid_amount > 0) {
        $this->status = 'partial';
    } elseif ($this->due_date < now()->toDateString()) {
        $this->status = 'overdue';
    } else {
        $this->status = 'pending';
    }
    
    $this->save();
}
```

**With this:**
```php
public function updateBalance(): void
{
    $totalPaid = $this->payments()->where('status', 'completed')->sum('amount');
    
    // Cap paid_amount at total_amount to prevent negative balances
    // If overpaid, paid_amount = total_amount, remaining_balance = 0
    $this->paid_amount = min($totalPaid, $this->total_amount);
    $this->remaining_balance = max(0, $this->total_amount - $this->paid_amount);
    
    // Update status based on balance
    if ($this->remaining_balance <= 0) {
        $this->status = 'paid';
        $this->paid_date = now()->toDateString();
    } elseif ($this->paid_amount > 0) {
        $this->status = 'partial';
    } elseif ($this->due_date < now()->toDateString()) {
        $this->status = 'overdue';
    } else {
        $this->status = 'pending';
    }
    
    $this->save();
}
```

---

### File 2: `app/Filament/Staff/Resources/PaymentResource/Pages/CreatePayment.php`

**Location:** Find the `mutateFormDataBeforeCreate()` method (around line 13-21)

**Replace this:**
```php
protected function mutateFormDataBeforeCreate(array $data): array
{
    // Auto-fill payment number if not set
    if (empty($data['payment_number'])) {
        $data['payment_number'] = \App\Models\Payment::generatePaymentNumber();
    }

    return $data;
}
```

**With this:**
```php
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
```

**Then, add this new method after `getRedirectUrl()` method:**
```php
protected function afterCreate(): void
{
    // Update the bill balance after payment is created
    $payment = $this->record;
    
    if ($payment->bill) {
        $payment->bill->updateBalance();
    }
}
```

---

### File 3: `app/Filament/Staff/Resources/PaymentResource/Pages/EditPayment.php`

**Location:** Add this method after `getHeaderActions()` method (around line 18)

**Add this new method:**
```php
protected function afterSave(): void
{
    // Update the bill balance after payment is updated
    $payment = $this->record;
    
    if ($payment->bill) {
        $payment->bill->updateBalance();
    }
}
```

---

### File 4: `app/Http/Controllers/DashboardController.php`

**Location:** Find the `billingDashboard()` method, look for the "Get recent payments" comment (around line 128-136)

**Replace this:**
```php
// Get recent payments
$recentPayments = Payment::whereHas('bill', function ($query) use ($user) {
    $query->where('client_id', $user->id);
})
->with(['bill.appointment.service'])
->orderBy('created_at', 'desc')
->limit(10)
->get();
```

**With this:**
```php
// Get recent payments - check both payment client_id and bill client_id
$recentPayments = Payment::where(function ($query) use ($user) {
        $query->where('client_id', $user->id)
            ->orWhereHas('bill', function ($q) use ($user) {
                $q->where('client_id', $user->id);
            });
    })
    ->with(['bill.appointment.service'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```

---

### File 5: `resources/views/filament/staff/modals/balance-details.blade.php`

**Location:** Find the "Recent Payments" section (around line 71-94)

**Replace the entire Recent Payments section:**
```php
<!-- Recent Payments -->
@if($bill->payments->count() > 0)
<div class="mt-6">
    <h4 class="font-medium text-gray-900 mb-3">Recent Payments</h4>
    <div class="space-y-2 max-h-40 overflow-y-auto">
        @foreach($bill->payments->take(5) as $payment)
        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
            <div>
                <p class="text-sm font-medium text-gray-900">₱{{ number_format($payment->amount, 2) }}</p>
                <p class="text-xs text-gray-700">{{ $payment->payment_date->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    @if($payment->status === 'completed') bg-green-100 text-green-800
                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
```

**With this (includes dark mode classes):**
```php
<!-- Recent Payments -->
@if($bill->payments->count() > 0)
<div class="mt-6">
    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Recent Payments</h4>
    <div class="space-y-2 max-h-40 overflow-y-auto">
        @foreach($bill->payments->take(5) as $payment)
        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-800 rounded">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($payment->amount, 2) }}</p>
                <p class="text-xs text-gray-700 dark:text-gray-300">{{ $payment->payment_date->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    @if($payment->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                    @elseif($payment->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
```

**Also update the rest of the modal for dark mode** - Find these sections and add dark mode classes:

1. **Balance Details heading** (around line 3):
   - Change: `text-gray-900` → `text-gray-900 dark:text-gray-100`
   - Change: `text-gray-600` → `text-gray-600 dark:text-gray-400`

2. **Bill Information section** (around line 10):
   - Change: `text-gray-900` → `text-gray-900 dark:text-gray-100`
   - Change: `text-gray-600` → `text-gray-600 dark:text-gray-400`
   - Add: `dark:text-gray-100` to amount spans

3. **Payment Progress section** (around line 38):
   - Change: `text-gray-900` → `text-gray-900 dark:text-gray-100`
   - Change: `bg-gray-200` → `bg-gray-200 dark:bg-gray-700`
   - Change: `bg-blue-600` → `bg-blue-600 dark:bg-blue-500`
   - Change: `text-gray-600` → `text-gray-600 dark:text-gray-400`

---

## Step 3: Run Commands on Hosted Server

After uploading the files, SSH into your hosted server and run these commands:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Recalculate all bill balances to fix negative balances
php artisan bills:recalculate-balances
```

---

## Step 4: Verify Changes

1. **Test Payment Creation:**
   - Go to Staff Panel → Payments → Create New Payment
   - Create a payment for a bill
   - Check that the bill's balance updates automatically

2. **Test Payment History:**
   - Log in as a client
   - Go to Billing Dashboard → Payment History tab
   - Verify payments are visible

3. **Test Dark Mode:**
   - Switch to dark mode in staff panel
   - Go to Payments → Click "View Balance" on any payment
   - Verify payment amounts are visible in dark mode

4. **Check for Negative Balances:**
   - Go to Client Billing Dashboard
   - Verify no bills show negative balances
   - Verify payment progress doesn't exceed 100%

---

## Troubleshooting

### If payments still don't show:
1. Check that `client_id` is set on payments: Run `php artisan payments:fix-client-ids`
2. Verify the query is working: Check browser console for errors
3. Clear browser cache: Hard refresh (Ctrl+F5)

### If negative balances persist:
1. Run `php artisan bills:recalculate-balances` again
2. Check that the `updateBalance()` method was updated correctly
3. Verify no old cached data exists

### If dark mode still has issues:
1. Clear view cache: `php artisan view:clear`
2. Hard refresh browser (Ctrl+F5)
3. Check that all dark mode classes were added correctly

---

## Files Changed Summary

1. ✅ `app/Models/Bill.php` - Fixed negative balance calculation
2. ✅ `app/Filament/Staff/Resources/PaymentResource/Pages/CreatePayment.php` - Auto-update bill on payment creation
3. ✅ `app/Filament/Staff/Resources/PaymentResource/Pages/EditPayment.php` - Auto-update bill on payment edit
4. ✅ `app/Http/Controllers/DashboardController.php` - Improved payment query
5. ✅ `resources/views/filament/staff/modals/balance-details.blade.php` - Fixed dark mode visibility

---

## Notes

- Always backup files before making changes
- Test changes in a staging environment first if possible
- Keep a log of what was changed and when
- If you have duplicate files in a `capstone-drveaesthetic` subdirectory, update those too

---

**Need Help?** If you encounter any issues, check:
- File permissions (should be readable by web server)
- PHP syntax errors (check server error logs)
- Laravel logs: `storage/logs/laravel.log`

