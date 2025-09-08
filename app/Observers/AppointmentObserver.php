<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Mail\AppointmentStatusNotification;
use Illuminate\Support\Facades\Mail;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // Optional: Send notification when appointment is created
        // This would be for new appointments created by staff
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
                    
                    // Send email notification to the client
                    if ($appointment->client && $appointment->client->email) {
                        Mail::to($appointment->client->email)
                            ->send(new AppointmentStatusNotification($appointment, $oldStatus));
                        
                        // Show notification in Filament (if available)
                        if (class_exists(\Filament\Notifications\Notification::class)) {
                            \Filament\Notifications\Notification::make()
                                ->title('Email Notification Sent')
                                ->body("Status update email sent to {$appointment->client->name} ({$appointment->client->email})")
                                ->success()
                                ->send();
                        }
                    }
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
