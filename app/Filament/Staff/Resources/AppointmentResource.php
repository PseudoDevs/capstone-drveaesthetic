<?php

namespace App\Filament\Staff\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use App\Models\ClinicService;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Staff\Resources\AppointmentResource\Pages;
use App\Filament\Staff\Resources\AppointmentResource\RelationManagers;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Client Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name', fn (Builder $query) => $query->where('role', 'Client'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'service_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('staff_id')
                    ->label('Assigned Staff')
                    ->relationship('staff', 'name', fn (Builder $query) => $query->whereIn('role', ['Staff', 'Doctor']))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->label('Appointment Date')
                    ->required()
                    ->native(false),
                Forms\Components\TimePicker::make('appointment_time')
                    ->label('Appointment Time')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'PENDING' => 'Pending',
                        'SCHEDULED' => 'Scheduled',
                        'ON-GOING' => 'On-Going',
                        'COMPLETED' => 'Completed',
                        'CANCELLED' => 'Cancelled',
                        'DECLINED' => 'Declined',
                        'RESCHEDULE' => 'Reschedule',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Payment Received')
                    ->default(false),
                Forms\Components\Toggle::make('is_rescheduled')
                    ->label('Rescheduled')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.service_name')
                    ->label('Service')
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
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Assigned Staff')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'SCHEDULED' => 'info',
                        'ON-GOING' => 'primary',
                        'COMPLETED' => 'success',
                        'CANCELLED' => 'danger',
                        'DECLINED' => 'danger',
                        'RESCHEDULE' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'PENDING' => 'Pending',
                        'SCHEDULED' => 'Scheduled',
                        'ON-GOING' => 'On-Going',
                        'COMPLETED' => 'Completed',
                        'CANCELLED' => 'Cancelled',
                        'DECLINED' => 'Declined',
                        'RESCHEDULE' => 'Reschedule',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_rescheduled')
                    ->label('Rescheduled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('staff_id')
                    ->label('Assigned Staff')
                    ->relationship('staff', 'name')
                    ->preload(),
                Tables\Filters\Filter::make('is_paid')
                    ->label('Payment Status')
                    ->query(fn (Builder $query): Builder => $query->where('is_paid', true))
                    ->toggle(),
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
                                'PENDING' => 'Pending',
                                'SCHEDULED' => 'Scheduled',
                                'ON-GOING' => 'On-Going',
                                'COMPLETED' => 'Completed',
                                'CANCELLED' => 'Cancelled',
                                'DECLINED' => 'Declined',
                                'RESCHEDULE' => 'Reschedule',
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('update_status_scheduled')
                        ->label('Set to Scheduled')
                        ->icon('heroicon-o-calendar')
                        ->color('info')
                        ->visible(fn ($record) => in_array($record->status, ['PENDING']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'SCHEDULED'])),
                    Tables\Actions\Action::make('update_status_ongoing')
                        ->label('Set to On-Going')
                        ->icon('heroicon-o-play')
                        ->color('primary')
                        ->visible(fn ($record) => in_array($record->status, ['PENDING', 'SCHEDULED']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'ON-GOING'])),
                    Tables\Actions\Action::make('update_status_completed')
                        ->label('Set to Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => in_array($record->status, ['PENDING', 'SCHEDULED', 'ON-GOING']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'COMPLETED'])),
                    Tables\Actions\Action::make('update_status_cancelled')
                        ->label('Set to Cancelled')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['PENDING', 'SCHEDULED']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'CANCELLED'])),
                    Tables\Actions\Action::make('update_status_declined')
                        ->label('Set to Declined')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['PENDING']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'DECLINED'])),
                ])
                    ->label('Change Status')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button()
                    ->visible(fn ($record) => !in_array($record->status, ['COMPLETED', 'DECLINED', 'CANCELLED', 'RESCHEDULE'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('appointment_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
