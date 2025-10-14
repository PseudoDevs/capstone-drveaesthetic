<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $oldStatus;

    public function __construct(Appointment $appointment, string $oldStatus = null)
    {
        $this->appointment = $appointment;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusMessages = [
            'pending' => 'Your appointment is pending confirmation.',
            'confirmed' => 'Your appointment has been confirmed.',
            'in_progress' => 'Your appointment is now in progress.',
            'completed' => 'Your appointment has been completed.',
            'cancelled' => 'Your appointment has been cancelled.',
            'no_show' => 'You were marked as no-show for your appointment.',
        ];

        $statusMessage = $statusMessages[$this->appointment->status] ?? 'Your appointment status has been updated.';

        return (new MailMessage)
            ->subject('Appointment Status Update - Dr. Ve Aesthetic')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($statusMessage)
            ->line('**Appointment Details:**')
            ->line('Service: ' . ($this->appointment->service->service_name ?? 'Service'))
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->line('Staff: ' . ($this->appointment->staff->name ?? 'Staff Member'))
            ->line('Status: ' . ucfirst($this->appointment->status))
            ->action('View Appointment', url('/users/dashboard'))
            ->salutation('Thank you for choosing Dr. Ve Aesthetic!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_status_update',
            'title' => 'Appointment Status Updated',
            'message' => 'Your appointment status has been updated to: ' . ucfirst($this->appointment->status),
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
            'status' => $this->appointment->status,
        ];
    }
}
