<?php

namespace App\Filament\Client\Widgets;

use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class UpcomingAppointmentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Appointments';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()
                    ->where('client_id', Filament::auth()->id())
                    ->whereIn('status', ['PENDING', 'SCHEDULED'])
                    ->where('appointment_date', '>=', now())
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time'),
                Tables\Columns\TextColumn::make('service.service_name')
                    ->label('Service'),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Staff'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'SCHEDULED' => 'info',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('form_type')
                    ->label('Appointment Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => 
                        $state ? \App\FormType::from($state)->getLabel() : 'No Form Type'
                    )
                    ->color(fn (?string $state): string => match ($state) {
                        'medical_information' => 'info',
                        'consent_waiver' => 'warning',
                        default => 'gray'
                    }),
                Tables\Columns\IconColumn::make('form_completed')
                    ->label('Form Status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewAppointments')
                    ->label('View All')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->url('/client/appointments'),
            ])
            ->emptyStateHeading('No upcoming appointments')
            ->emptyStateDescription('Book your first appointment to get started!')
            ->emptyStateActions([
                // Remove create action since appointment creation is disabled for clients
            ]);
    }
}