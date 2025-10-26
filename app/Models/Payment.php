<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'bill_id',
        'client_id',
        'appointment_id',
        'received_by',
        'amount',
        'payment_method',
        'payment_reference',
        'notes',
        'status',
        'payment_date',
        'processed_at',
        'bank_name',
        'check_number',
        'check_date',
        'transaction_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'check_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Generate unique payment number
    public static function generatePaymentNumber(): string
    {
        do {
            $number = 'PAY-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('payment_number', $number)->exists());

        return $number;
    }

    // Process payment
    public function process(): void
    {
        $this->status = 'completed';
        $this->processed_at = now();
        $this->save();

        // Update bill balance
        $this->bill->updateBalance();
    }

    // Check if payment is completed
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Get payment method display name
    public function getPaymentMethodDisplayAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer',
            'card' => 'Card',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    // Get status display name
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            default => 'Unknown',
        };
    }
}