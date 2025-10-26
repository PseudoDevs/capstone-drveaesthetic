<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'appointment_id',
        'client_id',
        'created_by',
        'bill_type',
        'payment_type',
        'total_installments',
        'down_payment',
        'installment_amount',
        'description',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'remaining_balance',
        'status',
        'bill_date',
        'due_date',
        'paid_date',
        'notes',
        'terms_conditions',
        'is_recurring',
        'recurring_frequency',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'bill_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Generate unique bill number
    public static function generateBillNumber(): string
    {
        do {
            $number = 'BILL-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('bill_number', $number)->exists());

        return $number;
    }

    // Calculate remaining balance
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

    // Check if bill is overdue
    public function isOverdue(): bool
    {
        return $this->due_date < now()->toDateString() && $this->status !== 'paid';
    }

    // Get payment progress percentage
    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        return round(($this->paid_amount / $this->total_amount) * 100, 2);
    }

    // Check if bill is fully paid
    public function isFullyPaid(): bool
    {
        return $this->remaining_balance <= 0;
    }

    // Get days until due
    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

    // Check if bill uses staggered payment
    public function isStaggeredPayment(): bool
    {
        return $this->payment_type === 'staggered';
    }

    // Get installment number based on payment count
    public function getNextInstallmentNumber(): int
    {
        $completedPayments = $this->payments()->where('status', 'completed')->count();
        return $completedPayments + 1;
    }

    // Check if down payment is made
    public function isDownPaymentMade(): bool
    {
        if (!$this->isStaggeredPayment()) {
            return false;
        }
        return $this->paid_amount >= $this->down_payment;
    }

    // Get expected next payment amount
    public function getNextPaymentAmount(): float
    {
        if (!$this->isStaggeredPayment()) {
            return $this->remaining_balance;
        }

        // If down payment not made yet
        if (!$this->isDownPaymentMade()) {
            return $this->down_payment;
        }

        // Return installment amount or remaining balance (whichever is smaller)
        return min($this->installment_amount, $this->remaining_balance);
    }
}