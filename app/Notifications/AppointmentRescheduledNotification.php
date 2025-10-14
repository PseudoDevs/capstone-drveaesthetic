<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRescheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $oldDate;
    protected $oldTime;

    public function __construct(Appointment $appointment, $oldDate = null, $oldTime = null)
    {
        $this->appointment = $appointment;
        $this->oldDate = $oldDate;
        $this->oldTime = $oldTime;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Appointment Rescheduled - Dr. Ve Aesthetic')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been rescheduled.')
            ->line('**New Appointment Details:**')
            ->line('Service: ' . ($this->appointment->service->service_name ?? 'Service'))
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->line('Staff: ' . ($this->appointment->staff->name ?? 'Staff Member'));

        if ($this->oldDate || $this->oldTime) {
            $message->line('**Previous Details:**');
            if ($this->oldDate) {
                $message->line('Previous Date: ' . $this->oldDate);
            }
            if ($this->oldTime) {
                $message->line('Previous Time: ' . $this->oldTime);
            }
        }

        $message->action('View Appointment', url('/users/dashboard'))
            ->line('Please arrive 15 minutes before your scheduled time.')
            ->line('If you need to make further changes, please contact us at least 24 hours in advance.')
            ->salutation('Thank you for choosing Dr. Ve Aesthetic!');

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_rescheduled',
            'title' => 'Appointment Rescheduled',
            'message' => 'Your appointment for ' . ($this->appointment->service->service_name ?? 'Service') . ' has been rescheduled.',
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
        ];
    }
}
