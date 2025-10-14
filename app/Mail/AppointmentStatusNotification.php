<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;
    public $oldStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, string $oldStatus = null)
    {
        $this->appointment = $appointment;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Status Update - ' . ($this->appointment->service->service_name ?? 'Appointment'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-status-notification',
            with: [
                'appointment' => $this->appointment,
                'oldStatus' => $this->oldStatus,
                'clientName' => $this->appointment->client->name ?? 'Client',
                'serviceName' => $this->appointment->service->service_name ?? 'Service',
                'staffName' => $this->appointment->staff->name ?? 'Staff Member',
                'appointmentDate' => $this->appointment->appointment_date,
                'appointmentTime' => $this->appointment->appointment_time,
                'status' => $this->appointment->status,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
