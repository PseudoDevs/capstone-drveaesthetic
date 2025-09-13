<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppointmentCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;

    protected static ?string $heading = 'Appointments Calendar';
    protected static ?int $sort = 5;

    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::query()
            ->with(['client', 'service', 'staff'])
            ->whereBetween('appointment_date', [$fetchInfo['start'], $fetchInfo['end']])
            ->get()
            ->map(
                fn (Appointment $appointment) => [
                    'id' => $appointment->id,
                    'title' => $appointment->client->name . ' - ' . $appointment->service->service_name,
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'end' => $appointment->appointment_date->format('Y-m-d') . 'T' .
                        Carbon::parse($appointment->appointment_time)->addHour()->format('H:i:s'),
                    'backgroundColor' => $this->getEventColor($appointment->status),
                    'borderColor' => $this->getEventColor($appointment->status),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'client' => $appointment->client->name,
                        'service' => $appointment->service->service_name,
                        'staff' => $appointment->staff->name ?? 'Not assigned',
                        'status' => $appointment->status,
                        'is_paid' => $appointment->is_paid,
                        'is_rescheduled' => $appointment->is_rescheduled,
                    ],
                ]
            )
            ->toArray();
    }

    protected function getEventColor(string $status): string
    {
        return match ($status) {
            'PENDING' => '#f59e0b',     // Yellow/Orange
            'CONFIRMED' => '#3b82f6',   // Blue
            'ON-GOING' => '#8b5cf6',    // Purple
            'COMPLETED' => '#10b981',   // Green
            'CANCELLED' => '#ef4444',   // Red
            'DECLINED' => '#6b7280',    // Gray
            default => '#6b7280',       // Gray
        };
    }

    protected function headerActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->mountUsing(
            //         function (array $arguments, array $data) {
            //             $data['appointment_date'] = $arguments['start'] ?? null;
            //             $data['appointment_time'] = $arguments['start'] ?
            //                 Carbon::parse($arguments['start'])->format('H:i:s') : null;

            //             return $data;
            //         }
            //     ),
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->mountUsing(
                    function (Model $record, array $arguments, array $data) {
                        $data['appointment_date'] = $arguments['event']['start'] ?? $record->appointment_date;
                        $data['appointment_time'] = $arguments['event']['start'] ?
                            Carbon::parse($arguments['event']['start'])->format('H:i:s') : $record->appointment_time;

                        return $data;
                    }
                ),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->infolist([
                    \Filament\Infolists\Components\TextEntry::make('client.name')
                        ->label('Client'),
                    \Filament\Infolists\Components\TextEntry::make('service.service_name')
                        ->label('Service'),
                    \Filament\Infolists\Components\TextEntry::make('staff.name')
                        ->label('Staff'),
                    \Filament\Infolists\Components\TextEntry::make('appointment_date')
                        ->label('Date')
                        ->date(),
                    \Filament\Infolists\Components\TextEntry::make('appointment_time')
                        ->label('Time')
                        ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('g:i A')),
                    \Filament\Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match (strtolower($state)) {
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'on-going' => 'primary',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            'declined' => 'gray',
                            default => 'gray',
                        }),
                    \Filament\Infolists\Components\IconEntry::make('is_paid')
                        ->label('Paid')
                        ->boolean(),
                    \Filament\Infolists\Components\IconEntry::make('is_rescheduled')
                        ->label('Rescheduled')
                        ->boolean(),
                ]),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('client_id')
                ->label('Client')
                ->relationship('client', 'name', fn ($query) => $query->where('role', 'Client'))
                ->searchable()
                ->required(),
            \Filament\Forms\Components\Select::make('service_id')
                ->label('Service')
                ->relationship('service', 'service_name')
                ->searchable()
                ->required(),
            \Filament\Forms\Components\Select::make('staff_id')
                ->label('Staff')
                ->relationship('staff', 'name', fn ($query) => $query->whereIn('role', ['Staff', 'Doctor']))
                ->searchable()
                ->required(),
            \Filament\Forms\Components\DatePicker::make('appointment_date')
                ->label('Date')
                ->required(),
            \Filament\Forms\Components\TimePicker::make('appointment_time')
                ->label('Time')
                ->required(),
            \Filament\Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'PENDING' => 'Pending',
                    'CONFIRMED' => 'Confirmed',
                    'ON-GOING' => 'On-Going',
                    'COMPLETED' => 'Completed',
                    'CANCELLED' => 'Cancelled',
                    'DECLINED' => 'Declined',
                ])
                ->default('PENDING')
                ->required(),
            \Filament\Forms\Components\Toggle::make('is_paid')
                ->label('Paid'),
        ];
    }

    public function config(): array
    {
        return [
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
            ],
            'initialView' => 'dayGridMonth',
            'height' => 'auto',
            'aspectRatio' => 1.8,
            'slotMinTime' => '08:00:00',
            'slotMaxTime' => '18:00:00',
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5, 6], // Monday to Saturday
                'startTime' => '08:00',
                'endTime' => '17:00',
            ],
            'eventDisplay' => 'block',
            'displayEventTime' => true,
            'displayEventEnd' => false,
            'weekends' => true,
            'selectable' => true,
            'selectMirror' => true,
            'editable' => false,
            'eventResizableFromStart' => false,
            'eventDurationEditable' => false,
            'dayMaxEvents' => 3,
            'moreLinkClick' => 'popover',
        ];
    }
}