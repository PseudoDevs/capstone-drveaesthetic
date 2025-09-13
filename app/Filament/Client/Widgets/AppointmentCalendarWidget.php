<?php

namespace App\Filament\Client\Widgets;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Facades\Filament;

class AppointmentCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;

    protected static ?string $heading = 'My Appointment Calendar';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function fetchEvents(array $fetchInfo): array
    {
        // Get current authenticated client
        $clientId = Filament::auth()->id();

        return Appointment::query()
            ->where('client_id', $clientId)
            ->whereDate('appointment_date', '>=', $fetchInfo['start'])
            ->whereDate('appointment_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->service->service_name . ' - ' . $appointment->staff->name,
                    'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                    'backgroundColor' => $this->getEventColor($appointment->status),
                    'borderColor' => $this->getEventColor($appointment->status),
                    'extendedProps' => [
                        'status' => $appointment->status,
                        'service' => $appointment->service->service_name,
                        'staff' => $appointment->staff->name,
                        'form_completed' => $appointment->form_completed,
                        'appointment_time' => $appointment->appointment_time,
                        'description' => $appointment->service->service_name . ' with ' . $appointment->staff->name .
                                       "\nStatus: " . $appointment->status .
                                       "\nForm Completed: " . ($appointment->form_completed ? 'Yes' : 'No'),
                    ],
                ];
            })
            ->toArray();
    }

    protected function getEventColor(string $status): string
    {
        return match ($status) {
            'PENDING' => '#f59e0b',      // amber-500
            'SCHEDULED' => '#3b82f6',    // blue-500
            'COMPLETED' => '#10b981',    // emerald-500
            'CANCELLED' => '#ef4444',    // red-500
            'DECLINED' => '#ef4444',     // red-500
            default => '#6b7280',        // gray-500
        };
    }

    public function getFormSchema(): array
    {
        return [];
    }

    // Disable any create functionality
    public function onEventClick(array $info): void
    {
        // Prevent any click-to-create functionality
    }

    public function onDateSelect(string $start, ?string $end, bool $allDay, ?array $view, ?array $resource): void
    {
        // Prevent any date selection create functionality
    }

    protected function headerActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }


    public function config(): array
    {
        return [
            'firstDay' => 1, // Monday
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
            ],
            'initialView' => 'dayGridMonth',
            'navLinks' => true,
            'editable' => false,
            'selectable' => false,
            'selectMirror' => false,
            'dayMaxEvents' => true,
            'eventDisplay' => 'block',
            'height' => 600,
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5, 6], // Monday - Saturday
                'startTime' => '08:00',
                'endTime' => '18:00',
            ],
            'slotMinTime' => '08:00:00',
            'slotMaxTime' => '19:00:00',
            'weekends' => true,
            'eventDidMount' => [
                'callback' => 'function(info) {
                    info.el.setAttribute("title", info.event.extendedProps.description);
                    info.el.style.cursor = "pointer";
                }'
            ],
            'eventClick' => [
                'callback' => 'function(info) {
                    const props = info.event.extendedProps;
                    alert("Appointment Details:\\n\\n" +
                          "Service: " + props.service + "\\n" +
                          "Staff: " + props.staff + "\\n" +
                          "Time: " + props.appointment_time + "\\n" +
                          "Status: " + props.status + "\\n" +
                          "Form Completed: " + (props.form_completed ? "Yes" : "No"));
                }'
            ],
        ];
    }
}
