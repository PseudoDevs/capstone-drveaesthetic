<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Confirmed - Dr. Ve Aesthetic')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been confirmed.')
            ->line('**Appointment Details:**')
            ->line('Service: ' . ($this->appointment->service->service_name ?? 'Service'))
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->line('Staff: ' . ($this->appointment->staff->name ?? 'Staff Member'))
            ->line('Price: ₱' . number_format($this->appointment->service->price ?? 0, 2))
            ->action('View Appointment', url('/users/dashboard'))
            ->line('Please arrive 15 minutes before your scheduled time.')
            ->line('If you need to reschedule or cancel, please contact us at least 24 hours in advance.')
            ->salutation('Thank you for choosing Dr. Ve Aesthetic!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_confirmation',
            'title' => 'Appointment Confirmed',
            'message' => 'Your appointment for ' . ($this->appointment->service->service_name ?? 'Service') . ' has been confirmed.',
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
        ];
    }
}
