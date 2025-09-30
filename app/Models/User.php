<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'phone',
        'date_of_birth',
        'address',
        'email_verified_at',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'client_id');
    }

    public function assignedAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'staff_id');
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // If it's a full URL (from Google OAuth), return as is
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // Otherwise, it's a local file stored in public/storage
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    // Filament panel access control
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === 'Admin',
            'staff' => in_array($this->role, ['Staff', 'Doctor']),
            default => false,
        };
    }

    // Notification preferences relationship
    public function notificationPreferences(): HasOne
    {
        return $this->hasOne(NotificationPreference::class);
    }

    // Get or create notification preferences
    public function getNotificationPreferences(): NotificationPreference
    {
        return $this->notificationPreferences ?? $this->notificationPreferences()->create(
            NotificationPreference::getDefaults()
        );
    }

    // Check if user wants to receive specific notification type
    public function wantsNotification(string $type): bool
    {
        $preferences = $this->getNotificationPreferences();
        
        return match($type) {
            'appointment_confirmation' => $preferences->appointment_confirmations,
            'appointment_reminder_24h' => $preferences->appointment_reminders_24h,
            'appointment_reminder_2h' => $preferences->appointment_reminders_2h,
            'appointment_cancellation' => $preferences->appointment_cancellations,
            'feedback_request' => $preferences->feedback_requests,
            'service_update' => $preferences->service_updates,
            'promotional_offer' => $preferences->promotional_offers,
            'newsletter' => $preferences->newsletter,
            default => $preferences->email_notifications,
        };
    }

    // Update user's last activity timestamp
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    // Check if user is currently online (active within last 5 minutes)
    public function isOnline(): bool
    {
        return $this->last_activity_at && $this->last_activity_at->isAfter(now()->subMinutes(5));
    }
}
