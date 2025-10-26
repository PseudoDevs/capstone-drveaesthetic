<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicService extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'staff_id',
        'service_name',
        'description',
        'thumbnail',
        'duration',
        'price',
        'status',
        'allows_staggered_payment',
        'min_installments',
        'max_installments',
        'down_payment_percentage',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'service_id');
    }

    /**
     * Check if this service allows staggered payments
     */
    public function allowsStaggeredPayment(): bool
    {
        return $this->allows_staggered_payment;
    }

    /**
     * Calculate down payment amount
     */
    public function calculateDownPayment(): float
    {
        if (!$this->allowsStaggeredPayment()) {
            return $this->price;
        }
        
        return $this->price * ($this->down_payment_percentage / 100);
    }

    /**
     * Calculate installment amount
     */
    public function calculateInstallmentAmount(int $installments): float
    {
        if (!$this->allowsStaggeredPayment() || $installments <= 1) {
            return $this->price;
        }
        
        $downPayment = $this->calculateDownPayment();
        $remainingAmount = $this->price - $downPayment;
        
        return $remainingAmount / ($installments - 1);
    }

    /**
     * Get available installment options
     */
    public function getInstallmentOptions(): array
    {
        if (!$this->allowsStaggeredPayment()) {
            return [];
        }
        
        $options = [];
        $min = $this->min_installments ?? 2;
        $max = $this->max_installments ?? 6;
        
        for ($i = $min; $i <= $max; $i++) {
            $downPayment = $this->calculateDownPayment();
            $installmentAmount = $this->calculateInstallmentAmount($i);
            $totalAmount = $downPayment + ($installmentAmount * ($i - 1));
            
            $options[] = [
                'installments' => $i,
                'down_payment' => $downPayment,
                'installment_amount' => $installmentAmount,
                'total_amount' => $totalAmount,
            ];
        }
        
        return $options;
    }
}
