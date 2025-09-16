<?php

namespace App\Filament\Staff\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Infolists;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use App\Models\Appointment;
use App\Enums\AppointmentType;
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
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false),
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $client = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'] ?? null,
                            'date_of_birth' => $data['date_of_birth'] ?? null,
                            'address' => $data['address'] ?? null,
                            'role' => 'Client',
                            'password' => Hash::make('password123'), // Default password
                        ]);

                        return $client->id;
                    }),
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'service_name')
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
                Forms\Components\Select::make('appointment_type')
                    ->label('Appointment Type')
                    ->options([
                        AppointmentType::ONLINE->value => AppointmentType::ONLINE->label(),
                        AppointmentType::WALK_IN->value => AppointmentType::WALK_IN->label(),
                    ])
                    ->default(AppointmentType::ONLINE->value)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state === AppointmentType::WALK_IN->value) {
                            $set('appointment_date', now()->format('Y-m-d'));
                            $set('appointment_time', now()->format('H:i'));
                        }
                    }),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['client', 'service.category', 'staff']))
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
                Tables\Columns\TextColumn::make('appointment_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (AppointmentType $state): string => match ($state) {
                        AppointmentType::ONLINE => 'info',
                        AppointmentType::WALK_IN => 'warning',
                    })
                    ->formatStateUsing(fn (AppointmentType $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'scheduled' => 'info',
                        'on-going' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'declined' => 'danger',
                        'reschedule' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
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
                SelectFilter::make('appointment_type')
                    ->label('Appointment Type')
                    ->options([
                        AppointmentType::ONLINE->value => AppointmentType::ONLINE->label(),
                        AppointmentType::WALK_IN->value => AppointmentType::WALK_IN->label(),
                    ]),
                Tables\Filters\Filter::make('is_paid')
                    ->label('Payment Status')
                    ->query(fn (Builder $query): Builder => $query->where('is_paid', true))
                    ->toggle(),
            ])
            ->actions([
                // Quick Status Actions - shown first based on current status
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Appointment')
                    ->modalDescription('Are you sure you want to confirm this appointment?')
                    ->action(fn ($record) => $record->update(['status' => 'scheduled'])),

                Tables\Actions\Action::make('start_appointment')
                    ->label('Start')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'scheduled')
                    ->requiresConfirmation()
                    ->modalHeading('Start Appointment')
                    ->modalDescription('Mark this appointment as ongoing?')
                    ->action(fn ($record) => $record->update(['status' => 'on-going'])),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'on-going')
                    ->requiresConfirmation()
                    ->modalHeading('Complete Appointment')
                    ->modalDescription('Mark this appointment as completed?')
                    ->action(fn ($record) => $record->update(['status' => 'completed'])),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('warning')
                    ->visible(fn ($record) => !$record->is_paid)
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Paid')
                    ->modalDescription('Mark this appointment as paid?')
                    ->action(fn ($record) => $record->update(['is_paid' => true])),

                Tables\Actions\Action::make('reschedule')
                    ->label('Reschedule')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'scheduled']))
                    ->form([
                        Forms\Components\DatePicker::make('appointment_date')
                            ->label('New Date')
                            ->required(),
                        Forms\Components\TimePicker::make('appointment_time')
                            ->label('New Time')
                            ->required(),
                        Forms\Components\Select::make('appointment_type')
                            ->label('Appointment Type')
                            ->options([
                                AppointmentType::ONLINE->value => AppointmentType::ONLINE->label(),
                                AppointmentType::WALK_IN->value => AppointmentType::WALK_IN->label(),
                            ])
                            ->required(),
                    ])
                    ->fillForm(fn ($record) => [
                        'appointment_date' => $record->appointment_date,
                        'appointment_time' => $record->appointment_time,
                        'appointment_type' => $record->appointment_type,
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'appointment_date' => $data['appointment_date'],
                            'appointment_time' => $data['appointment_time'],
                            'appointment_type' => $data['appointment_type'],
                            'status' => 'scheduled',
                            'is_rescheduled' => $record->appointment_date != $data['appointment_date'] || $record->appointment_time != $data['appointment_time'],
                        ]);
                    })
                    ->modalHeading('Reschedule Appointment')
                    ->modalWidth('md'),

                // More Actions Dropdown
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('View Details')
                        ->icon('heroicon-o-eye')
                        ->slideOver()
                        ->modalWidth('xl')
                        ->infolist([
                            Infolists\Components\Section::make('Appointment Information')
                                ->schema([
                                    Infolists\Components\Grid::make(2)
                                        ->schema([
                                            Infolists\Components\TextEntry::make('client.name')
                                                ->label('Client Name')
                                                ->weight('bold'),
                                            Infolists\Components\TextEntry::make('client.email')
                                                ->label('Client Email')
                                                ->icon('heroicon-m-envelope'),
                                            Infolists\Components\TextEntry::make('service.service_name')
                                                ->label('Service')
                                                ->badge()
                                                ->color('primary'),
                                            Infolists\Components\TextEntry::make('service.price')
                                                ->label('Service Price')
                                                ->money('PHP')
                                                ->icon('heroicon-m-currency-dollar'),
                                            Infolists\Components\TextEntry::make('appointment_date')
                                                ->label('Appointment Date')
                                                ->date()
                                                ->icon('heroicon-m-calendar-days'),
                                            Infolists\Components\TextEntry::make('appointment_time')
                                                ->label('Appointment Time')
                                                ->time()
                                                ->icon('heroicon-m-clock'),
                                            Infolists\Components\TextEntry::make('appointment_type')
                                                ->label('Appointment Type')
                                                ->badge()
                                                ->color(fn (AppointmentType $state): string => match ($state) {
                                                    AppointmentType::ONLINE => 'info',
                                                    AppointmentType::WALK_IN => 'warning',
                                                })
                                                ->formatStateUsing(fn (AppointmentType $state): string => $state->label()),
                                            Infolists\Components\TextEntry::make('status')
                                                ->label('Status')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'pending' => 'warning',
                                                    'scheduled' => 'info',
                                                    'on-going' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    'declined' => 'danger',
                                                    'reschedule' => 'secondary',
                                                    default => 'gray',
                                                })
                                                ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                                        ]),
                                    Infolists\Components\Grid::make(3)
                                        ->schema([
                                            Infolists\Components\IconEntry::make('is_paid')
                                                ->label('Payment Received')
                                                ->boolean()
                                                ->trueIcon('heroicon-o-check-circle')
                                                ->falseIcon('heroicon-o-x-circle')
                                                ->trueColor('success')
                                                ->falseColor('danger'),
                                            Infolists\Components\IconEntry::make('is_rescheduled')
                                                ->label('Rescheduled')
                                                ->boolean()
                                                ->trueIcon('heroicon-o-arrow-path')
                                                ->falseIcon('heroicon-o-minus-circle')
                                                ->trueColor('warning')
                                                ->falseColor('gray'),
                                            Infolists\Components\TextEntry::make('created_at')
                                                ->label('Created At')
                                                ->dateTime()
                                                ->icon('heroicon-m-clock'),
                                        ]),
                                ]),
                            Infolists\Components\Section::make('Client Details')
                                ->schema([
                                    Infolists\Components\Grid::make(2)
                                        ->schema([
                                            Infolists\Components\TextEntry::make('client.phone')
                                                ->label('Client Phone')
                                                ->icon('heroicon-m-phone')
                                                ->placeholder('Not provided'),
                                            Infolists\Components\TextEntry::make('client.date_of_birth')
                                                ->label('Date of Birth')
                                                ->date()
                                                ->icon('heroicon-m-cake')
                                                ->placeholder('Not provided'),
                                            Infolists\Components\TextEntry::make('client.address')
                                                ->label('Client Address')
                                                ->icon('heroicon-m-map-pin')
                                                ->placeholder('Not provided')
                                                ->columnSpanFull(),
                                        ]),
                                ])
                                ->collapsible()
                                ->collapsed(),
                            Infolists\Components\Section::make('Service Details')
                                ->schema([
                                    Infolists\Components\Grid::make(2)
                                        ->schema([
                                            Infolists\Components\TextEntry::make('service.category.category_name')
                                                ->label('Service Category')
                                                ->badge()
                                                ->color('secondary'),
                                            Infolists\Components\TextEntry::make('service.duration')
                                                ->label('Service Duration')
                                                ->suffix(' minutes')
                                                ->icon('heroicon-m-clock'),
                                            Infolists\Components\TextEntry::make('service.description')
                                                ->label('Service Description')
                                                ->placeholder('No description available')
                                                ->columnSpanFull(),
                                        ]),
                                ])
                                ->collapsible(),
                        ]),


                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['pending', 'scheduled']))
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Appointment')
                        ->modalDescription('Are you sure you want to cancel this appointment?')
                        ->action(fn ($record) => $record->update(['status' => 'cancelled'])),

                    Tables\Actions\Action::make('decline')
                        ->label('Decline')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->modalHeading('Decline Appointment')
                        ->modalDescription('Are you sure you want to decline this appointment?')
                        ->action(fn ($record) => $record->update(['status' => 'declined'])),
                ])
                    ->label('More Actions')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button(),
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
            // 'create' => Pages\CreateAppointment::route('/create'),
            // 'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
