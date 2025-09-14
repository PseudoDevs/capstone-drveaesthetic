<?php

namespace App\Models;

use App\Enums\AppointmentType;
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
        'appointment_type',
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
        'appointment_type' => AppointmentType::class,
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

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    // Scopes for filtering appointments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeOnline($query)
    {
        return $query->where('appointment_type', AppointmentType::ONLINE);
    }

    public function scopeWalkIn($query)
    {
        return $query->where('appointment_type', AppointmentType::WALK_IN);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->appointment_date >= now()->format('Y-m-d');
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->appointment_date >= now()->format('Y-m-d');
    }

    public function isOnline()
    {
        return $this->appointment_type === AppointmentType::ONLINE;
    }

    public function isWalkIn()
    {
        return $this->appointment_type === AppointmentType::WALK_IN;
    }

    // Check for conflicts with same time slot
    public static function hasTimeConflict($date, $time, $excludeId = null)
    {
        $query = static::where('appointment_date', $date)
                      ->where('appointment_time', $time)
                      ->whereIn('status', ['pending', 'confirmed']);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    // Get formatted appointment time
    public function getFormattedTimeAttribute()
    {
        return date('h:i A', strtotime($this->appointment_time));
    }

    // Get formatted appointment date
    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('M d, Y');
    }
}
