<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'issued_by',
        'client_id',
        'appointment_id',
        'certificate_number',
        'certificate_type',
        'purpose',
        'recommendations',
        'restrictions',
        'additional_notes',
        'valid_from',
        'valid_until',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public static function generateCertificateNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $count = static::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return "MC-{$year}{$month}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
