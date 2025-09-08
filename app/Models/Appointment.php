<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'staff_id',
        'appointment_date',
        'appointment_time',
        'is_rescheduled',
        'is_paid',
        'status',
        'form_type',
        'form_completed',
        'medical_form_data',
        'consent_waiver_form_data',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'is_rescheduled' => 'boolean',
        'is_paid' => 'boolean',
        'form_completed' => 'boolean',
        'medical_form_data' => 'array',
        'consent_waiver_form_data' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ClinicService::class, 'service_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }
}
