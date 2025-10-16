<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Notifications\AppointmentConfirmationNotification;
use App\Notifications\AppointmentStatusNotification as StatusNotification;
use App\Notifications\FeedbackRequestNotification;
use App\Events\AppointmentStatusUpdated;
use App\Mail\AppointmentStatusNotification;
use Illuminate\Support\Facades\Mail;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // Send confirmation notification when appointment is created
        if ($appointment->client && $appointment->client->wantsNotification('appointment_confirmation')) {
            $appointment->client->notify(new AppointmentConfirmationNotification($appointment));
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        // Check if status was changed
        if ($appointment->isDirty('status')) {
            $oldStatus = $appointment->getOriginal('status');
            $newStatus = $appointment->status;

            // Only send email if status actually changed and it's not the initial creation
            if ($oldStatus !== $newStatus && $oldStatus !== null) {
                try {
                    // Load necessary relationships if not already loaded
                    $appointment->load(['client', 'service', 'staff']);

                    // Honor user's notification preferences
                    $client = $appointment->client;
                    if ($client && $client->email) {
                        $shouldSend = true;

                        // Map specific statuses to preference keys
                        $preferenceKey = match (strtolower($newStatus)) {
                            'scheduled', 'confirmed' => 'appointment_confirmation',
                            'cancelled', 'declined' => 'appointment_cancellation',
                            default => 'email_notifications',
                        };

                        $shouldSend = $client->wantsNotification($preferenceKey);

                        if ($shouldSend) {
                            // Use Notification (queued) rather than direct Mailable
                            $client->notify(new StatusNotification($appointment, $oldStatus));
                        }

                        // Show notification in Filament (if available)
                        if (class_exists(\Filament\Notifications\Notification::class)) {
                            \Filament\Notifications\Notification::make()
                                ->title('Email Notification Sent')
                                ->body("Status update email sent to {$appointment->client->name} ({$appointment->client->email})")
                                ->success()
                                ->send();
                        }
                    }

                    // Send feedback request when appointment is completed
                    if ($newStatus === 'completed' && $appointment->client->wantsNotification('feedback_request')) {
                        // Delay feedback request by 2 hours to allow for immediate post-treatment experience
                        $appointment->client->notify(
                            (new FeedbackRequestNotification($appointment))->delay(now()->addHours(2))
                        );
                    }

                    // Broadcast real-time update
                    broadcast(new AppointmentStatusUpdated($appointment, $oldStatus, $newStatus));
                } catch (\Exception $e) {
                    // Log the error but don't fail the update
                    \Log::error('Failed to send appointment status notification', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        // Optional: Send cancellation notification
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}
