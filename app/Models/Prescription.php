<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id',
        'client_id',
        'prescribed_by',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'notes',
        'prescribed_date',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function prescribedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    // Scope for prescriptions by client
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    // Scope for prescriptions by doctor/staff
    public function scopeByPrescriber($query, $prescriberId)
    {
        return $query->where('prescribed_by', $prescriberId);
    }

    // Get formatted prescription details
    public function getFormattedPrescriptionAttribute(): string
    {
        return "{$this->medication_name} - {$this->dosage}, {$this->frequency} for {$this->duration}";
    }
}
