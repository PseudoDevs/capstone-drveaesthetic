<?php

namespace App\Filament\Staff\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'service_name')
                    ->required(),
                Forms\Components\Select::make('staff_id')
                    ->label('Staff')
                    ->relationship('staff', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->label('Date')
                    ->required(),
                Forms\Components\TimePicker::make('appointment_time')
                    ->label('Time')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Paid'),
                Forms\Components\Toggle::make('is_rescheduled')
                    ->label('Rescheduled'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.service_name')
            ->columns([
                Tables\Columns\TextColumn::make('service.service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Staff')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('g:i A'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\IconColumn::make('is_rescheduled')
                    ->label('Rescheduled')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-minus'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->native(false)
                    ->options([
                        'PENDING' => 'PENDING',
                        'CONFIRMED' => 'CONFIRMED',
                        'COMPLETED' => 'COMPLETED',
                        'CANCELLED' => 'CANCELLED',
                        'DECLINED' => 'DECLINED',
                    ]),
                Tables\Filters\Filter::make('is_paid')
                    ->label('Paid')
                    ->query(fn (Builder $query): Builder => $query->where('is_paid', true))
                    ->toggle(),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_paid)
                    ->requiresConfirmation()
                    ->modalHeading('Mark Appointment as Paid')
                    ->modalDescription('Are you sure you want to mark this appointment as paid?')
                    ->action(fn ($record) => $record->update(['is_paid' => true])),
                Tables\Actions\Action::make('reschedule')
                    ->label('Reschedule')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => !$record->is_rescheduled)
                    ->form([
                        Forms\Components\DatePicker::make('appointment_date')
                            ->label('New Date')
                            ->required(),
                        Forms\Components\TimePicker::make('appointment_time')
                            ->label('New Time')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'PENDING' => 'PENDING',
                                'CONFIRMED' => 'CONFIRMED',
                                'COMPLETED' => 'COMPLETED',
                                'CANCELLED' => 'CANCELLED',
                                'DECLINED' => 'DECLINED',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_paid')
                            ->label('Mark as Paid'),
                    ])
                    ->fillForm(fn ($record) => [
                        'appointment_date' => $record->appointment_date,
                        'appointment_time' => $record->appointment_time,
                        'status' => $record->status,
                        'is_paid' => $record->is_paid,
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'appointment_date' => $data['appointment_date'],
                            'appointment_time' => $data['appointment_time'],
                            'status' => $data['status'],
                            'is_paid' => $data['is_paid'],
                            'is_rescheduled' => $record->appointment_date != $data['appointment_date'] || $record->appointment_time != $data['appointment_time'],
                        ]);
                    })
                    ->modalHeading('Reschedule Appointment')
                    ->modalWidth('md'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('appointment_date', 'desc');
    }
}
