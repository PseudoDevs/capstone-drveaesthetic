<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'appointment_confirmations',
        'appointment_reminders_24h',
        'appointment_reminders_2h',
        'appointment_cancellations',
        'feedback_requests',
        'service_updates',
        'promotional_offers',
        'newsletter',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'appointment_confirmations' => 'boolean',
        'appointment_reminders_24h' => 'boolean',
        'appointment_reminders_2h' => 'boolean',
        'appointment_cancellations' => 'boolean',
        'feedback_requests' => 'boolean',
        'service_updates' => 'boolean',
        'promotional_offers' => 'boolean',
        'newsletter' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getDefaults(): array
    {
        return [
            'email_notifications' => true,
            'appointment_confirmations' => true,
            'appointment_reminders_24h' => true,
            'appointment_reminders_2h' => true,
            'appointment_cancellations' => true,
            'feedback_requests' => true,
            'service_updates' => true,
            'promotional_offers' => false,
            'newsletter' => false,
        ];
    }
}
