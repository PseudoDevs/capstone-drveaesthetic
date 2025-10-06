<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackRequestNotification extends Notification implements ShouldQueue
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
            ->subject('How was your experience? - Dr. Ve Aesthetic')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for visiting Dr. Ve Aesthetic!')
            ->line('We hope you had a wonderful experience with your recent treatment.')
            ->line('**Service Details:**')
            ->line('Service: ' . $this->appointment->service->service_name)
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Staff: ' . $this->appointment->staff->name)
            ->line('Your feedback helps us improve our services and helps other clients make informed decisions.')
            ->action('Leave Feedback', url('/feedback'))
            ->line('**What you can share:**')
            ->line('• Rate your overall experience (1-5 stars)')
            ->line('• Share your thoughts about the service')
            ->line('• Tell us about our staff\'s professionalism')
            ->line('• Suggest any improvements')
            ->line('Your feedback is valuable to us and will be kept confidential.')
            ->salutation('Thank you for choosing Dr. Ve Aesthetic!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'feedback_request',
            'title' => 'Share Your Experience',
            'message' => 'We\'d love to hear about your experience with ' . $this->appointment->service->service_name . '.',
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'service_name' => $this->appointment->service->service_name,
        ];
    }
}
