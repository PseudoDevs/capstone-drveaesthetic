<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $oldStatus;
    public $newStatus;

    public function __construct(Appointment $appointment, string $oldStatus, string $newStatus)
    {
        $this->appointment = $appointment;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('appointment.' . $this->appointment->client_id),
            new PrivateChannel('staff.' . $this->appointment->staff_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'appointment_id' => $this->appointment->id,
            'client_id' => $this->appointment->client_id,
            'staff_id' => $this->appointment->staff_id,
            'service_name' => $this->appointment->service->service_name,
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_at' => $this->appointment->updated_at->toISOString(),
        ];
    }

    public function broadcastAs()
    {
        return 'appointment.status.updated';
    }
}
