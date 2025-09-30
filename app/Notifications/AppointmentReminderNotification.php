<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $reminderType;

    public function __construct(Appointment $appointment, string $reminderType = '24h')
    {
        $this->appointment = $appointment;
        $this->reminderType = $reminderType;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->reminderType === '24h' 
            ? 'Appointment Reminder - Tomorrow' 
            : 'Appointment Reminder - Today';

        $greeting = $this->reminderType === '24h' 
            ? 'Reminder: You have an appointment tomorrow!'
            : 'Reminder: You have an appointment today!';

        return (new MailMessage)
            ->subject($subject . ' - Dr. Ve Aesthetic')
            ->greeting($greeting)
            ->line('**Appointment Details:**')
            ->line('Service: ' . $this->appointment->service->service_name)
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->line('Staff: ' . $this->appointment->staff->name)
            ->line('Duration: ' . $this->appointment->service->duration . ' minutes')
            ->action('View Appointment', url('/users/dashboard'))
            ->line('**Important Reminders:**')
            ->line('• Please arrive 15 minutes before your scheduled time')
            ->line('• Bring a valid ID')
            ->line('• Avoid wearing makeup if it\'s a facial treatment')
            ->line('• Contact us immediately if you need to reschedule')
            ->salutation('We look forward to seeing you!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_reminder',
            'reminder_type' => $this->reminderType,
            'title' => 'Appointment Reminder',
            'message' => 'Reminder: You have an appointment for ' . $this->appointment->service->service_name . ' ' . 
                        ($this->reminderType === '24h' ? 'tomorrow' : 'today') . '.',
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
        ];
    }
}
