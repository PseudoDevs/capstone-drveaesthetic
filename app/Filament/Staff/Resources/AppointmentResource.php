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
                            Infolists\Components\Section::make('Pre-Assessment Forms')
                                ->schema([
                                    Infolists\Components\ViewEntry::make('assessment_status')
                                        ->label('')
                                        ->view('filament.forms.components.assessment-status-display'),
                                    Infolists\Components\ViewEntry::make('medical_form_data')
                                        ->label('')
                                        ->view('filament.forms.components.medical-form-display')
                                        ->visible(fn ($record) => !empty($record->medical_form_data)),
                                    Infolists\Components\ViewEntry::make('consent_waiver_form_data')
                                        ->label('')
                                        ->view('filament.forms.components.consent-form-display')
                                        ->visible(fn ($record) => !empty($record->consent_waiver_form_data)),
                                ])
                                ->collapsible()
                                ->collapsed(fn ($record) => !$record->form_completed),
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

                    Tables\Actions\Action::make('edit_medical_form')
                        ->label('Edit Medical Form')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->visible(fn ($record) => !empty($record->medical_form_data))
                        ->slideOver()
                        ->modalWidth('5xl')
                        ->form(function (Appointment $record) {
                            return static::getMedicalFormSchema($record);
                        })
                        ->fillForm(function (Appointment $record) {
                            $existingForm = $record->medical_form_data ?? [];
                            
                            return [
                                'medical_form_data' => [
                                    'patient_name' => $existingForm['patient_name'] ?? $record->client->name ?? '',
                                    'date' => $existingForm['date'] ?? $record->appointment_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                                    'address' => $existingForm['address'] ?? $record->client->address ?? '',
                                    'procedure' => $existingForm['procedure'] ?? $record->service->service_name ?? '',
                                    'allergy' => $existingForm['allergy'] ?? false,
                                    'diabetes' => $existingForm['diabetes'] ?? false,
                                    'thyroid_diseases' => $existingForm['thyroid_diseases'] ?? false,
                                    'stroke' => $existingForm['stroke'] ?? false,
                                    'heart_diseases' => $existingForm['heart_diseases'] ?? false,
                                    'kidney_diseases' => $existingForm['kidney_diseases'] ?? false,
                                    'hypertension' => $existingForm['hypertension'] ?? false,
                                    'asthma' => $existingForm['asthma'] ?? false,
                                    'medical_history_others' => $existingForm['medical_history_others'] ?? '',
                                    'pregnant' => $existingForm['pregnant'] ?? false,
                                    'lactating' => $existingForm['lactating'] ?? false,
                                    'smoker' => $existingForm['smoker'] ?? false,
                                    'alcoholic_drinker' => $existingForm['alcoholic_drinker'] ?? false,
                                    'maintenance_medications' => $existingForm['maintenance_medications'] ?? '',
                                ],
                            ];
                        })
                        ->action(function (Appointment $record, array $data) {
                            // Handle nested data structure from statePath
                            $medicalFormData = $data['medical_form_data'] ?? $data;
                            
                            $record->update([
                                'medical_form_data' => $medicalFormData,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Medical form updated successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('edit_consent_form')
                        ->label('Edit Consent Form')
                        ->icon('heroicon-o-document-check')
                        ->color('info')
                        ->visible(fn ($record) => !empty($record->consent_waiver_form_data))
                        ->slideOver()
                        ->modalWidth('5xl')
                        ->form(function (Appointment $record) {
                            return static::getConsentWaiverFormSchema($record);
                        })
                        ->fillForm(function (Appointment $record) {
                            $existingForm = $record->consent_waiver_form_data ?? [];
                            
                            return [
                                'consent_waiver_form_data' => [
                                    'patient_name' => $existingForm['patient_name'] ?? $record->client->name ?? '',
                                    'age' => $existingForm['age'] ?? ($record->client->date_of_birth ? \Carbon\Carbon::parse($record->client->date_of_birth)->age : ''),
                                    'civil_status' => $existingForm['civil_status'] ?? '',
                                    'residence' => $existingForm['residence'] ?? $record->client->address ?? '',
                                    'interviewed_advised_counselled' => $existingForm['interviewed_advised_counselled'] ?? false,
                                    'services_availed' => $existingForm['services_availed'] ?? $record->service->service_name ?? '',
                                    'hold_clinic_free_from_liabilities' => $existingForm['hold_clinic_free_from_liabilities'] ?? false,
                                    'read_understood_consent' => $existingForm['read_understood_consent'] ?? false,
                                    'acknowledge_right_to_record' => $existingForm['acknowledge_right_to_record'] ?? false,
                                    'signature_date' => $existingForm['signature_date'] ?? $record->appointment_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                                    'signature_location' => $existingForm['signature_location'] ?? 'Zone 1, San Jose, Iriga City',
                                    'signature_data' => $existingForm['signature_data'] ?? '',
                                ],
                            ];
                        })
                        ->action(function (Appointment $record, array $data) {
                            // Handle nested data structure from statePath
                            $consentFormData = $data['consent_waiver_form_data'] ?? $data;
                            
                            $record->update([
                                'consent_waiver_form_data' => $consentFormData,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Consent form updated successfully')
                                ->success()
                                ->send();
                        }),
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

    public static function getMedicalFormSchema(Appointment $appointment): array
    {
        $existingForm = $appointment->medical_form_data ?? [];

        return [
            Forms\Components\Section::make('Dr. Ve Aesthetic Clinic and Wellness Center')
                ->description('Zone 1, San Jose, Iriga City, Camarines Sur')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('patient_name')
                                ->label('Name:')
                                ->statePath('medical_form_data.patient_name')
                                ->default($existingForm['patient_name'] ?? $appointment->client->name)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('date')
                                ->label('Date:')
                                ->statePath('medical_form_data.date')
                                ->default($existingForm['date'] ?? now())
                                ->required()
                                ->native(false),
                        ]),
                    Forms\Components\Textarea::make('address')
                        ->label('Address:')
                        ->statePath('medical_form_data.address')
                        ->default($existingForm['address'] ?? $appointment->client->address ?? '')
                        ->required()
                        ->rows(2),
                    Forms\Components\TextInput::make('procedure')
                        ->label('Procedure:')
                        ->statePath('medical_form_data.procedure')
                        ->default($existingForm['procedure'] ?? $appointment->service->service_name)
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Section::make('Past Medical History:')
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Checkbox::make('allergy')
                                        ->label('Allergy')
                                        ->statePath('medical_form_data.allergy')
                                        ->default($existingForm['allergy'] ?? false),
                                    Forms\Components\Checkbox::make('diabetes')
                                        ->label('Diabetes')
                                        ->statePath('medical_form_data.diabetes')
                                        ->default($existingForm['diabetes'] ?? false),
                                    Forms\Components\Checkbox::make('thyroid_diseases')
                                        ->label('Thyroid diseases')
                                        ->statePath('medical_form_data.thyroid_diseases')
                                        ->default($existingForm['thyroid_diseases'] ?? false),
                                    Forms\Components\Checkbox::make('stroke')
                                        ->label('Stroke')
                                        ->statePath('medical_form_data.stroke')
                                        ->default($existingForm['stroke'] ?? false),
                                    Forms\Components\Checkbox::make('heart_diseases')
                                        ->label('Heart Diseases')
                                        ->statePath('medical_form_data.heart_diseases')
                                        ->default($existingForm['heart_diseases'] ?? false),
                                    Forms\Components\Checkbox::make('kidney_diseases')
                                        ->label('Kidney Diseases')
                                        ->statePath('medical_form_data.kidney_diseases')
                                        ->default($existingForm['kidney_diseases'] ?? false),
                                    Forms\Components\Checkbox::make('hypertension')
                                        ->label('Hypertension')
                                        ->statePath('medical_form_data.hypertension')
                                        ->default($existingForm['hypertension'] ?? false),
                                    Forms\Components\Checkbox::make('asthma')
                                        ->label('Asthma')
                                        ->statePath('medical_form_data.asthma')
                                        ->default($existingForm['asthma'] ?? false),
                                ]),
                            Forms\Components\Textarea::make('medical_history_others')
                                ->label('Others:')
                                ->statePath('medical_form_data.medical_history_others')
                                ->default($existingForm['medical_history_others'] ?? '')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Section::make('Are you?')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Checkbox::make('pregnant')
                                        ->label('Pregnant')
                                        ->statePath('medical_form_data.pregnant')
                                        ->default($existingForm['pregnant'] ?? false),
                                    Forms\Components\Checkbox::make('lactating')
                                        ->label('Lactating')
                                        ->statePath('medical_form_data.lactating')
                                        ->default($existingForm['lactating'] ?? false),
                                    Forms\Components\Checkbox::make('smoker')
                                        ->label('Smoker')
                                        ->statePath('medical_form_data.smoker')
                                        ->default($existingForm['smoker'] ?? false),
                                    Forms\Components\Checkbox::make('alcoholic_drinker')
                                        ->label('Alcoholic Drinker')
                                        ->statePath('medical_form_data.alcoholic_drinker')
                                        ->default($existingForm['alcoholic_drinker'] ?? false),
                                ]),
                        ]),

                    Forms\Components\Section::make('Maintenance Medications:')
                        ->schema([
                            Forms\Components\Textarea::make('maintenance_medications')
                                ->label('')
                                ->statePath('medical_form_data.maintenance_medications')
                                ->default($existingForm['maintenance_medications'] ?? '')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }

    public static function getConsentWaiverFormSchema(Appointment $appointment): array
    {
        $existingForm = $appointment->consent_waiver_form_data ?? [];

        return [
            Forms\Components\Section::make('Dr. Ve Aesthetic Clinic and Wellness Center')
                ->description('Zone 1, San Jose, Iriga City, Camarines Sur')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('patient_name')
                                ->label('Name:')
                                ->statePath('consent_waiver_form_data.patient_name')
                                ->default($existingForm['patient_name'] ?? $appointment->client->name)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('age')
                                ->label('Age:')
                                ->statePath('consent_waiver_form_data.age')
                                ->default($existingForm['age'] ?? ($appointment->client->date_of_birth ? \Carbon\Carbon::parse($appointment->client->date_of_birth)->age : ''))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(150),
                        ]),
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('civil_status')
                                ->label('Civil Status:')
                                ->statePath('consent_waiver_form_data.civil_status')
                                ->options([
                                    'single' => 'Single',
                                    'married' => 'Married',
                                ])
                                ->default($existingForm['civil_status'] ?? '')
                                ->required()
                                ->native(false),
                            Forms\Components\Textarea::make('residence')
                                ->label('Residence:')
                                ->statePath('consent_waiver_form_data.residence')
                                ->default($existingForm['residence'] ?? $appointment->client->address ?? '')
                                ->required()
                                ->rows(2),
                        ]),

                    Forms\Components\Section::make('Agreement and Consent')
                        ->description('Please read each statement carefully and check all that apply')
                        ->schema([
                            Forms\Components\Checkbox::make('interviewed_advised_counselled')
                                ->label('I have been interviewed, advised and counselled regarding my medical history and health status.')
                                ->statePath('consent_waiver_form_data.interviewed_advised_counselled')
                                ->default($existingForm['interviewed_advised_counselled'] ?? false)
                                ->required(),
                            Forms\Components\Textarea::make('services_availed')
                                ->label('I availed the service/s of:')
                                ->statePath('consent_waiver_form_data.services_availed')
                                ->default($existingForm['services_availed'] ?? $appointment->service->service_name)
                                ->required()
                                ->rows(2),
                            Forms\Components\Checkbox::make('hold_clinic_free_from_liabilities')
                                ->label('I hold Dr. Ve Aesthetic Clinic and Wellness Center and its employees, officers, medical practitioner / nurse/aesthetician free from all liabilities and damages resulting to claims, suits and action for injuries, caused by or arising out from any untoward events, intentional/ unintentional acts regarding my procedure/s.')
                                ->statePath('consent_waiver_form_data.hold_clinic_free_from_liabilities')
                                ->default($existingForm['hold_clinic_free_from_liabilities'] ?? false)
                                ->required(),
                            Forms\Components\Checkbox::make('read_understood_consent')
                                ->label('I have read and understood the foregoing and acknowledge my consent to this waiver by signing hereof.')
                                ->statePath('consent_waiver_form_data.read_understood_consent')
                                ->default($existingForm['read_understood_consent'] ?? false)
                                ->required(),
                            Forms\Components\Checkbox::make('acknowledge_right_to_record')
                                ->label('I acknowledge that Dr. Ve Aesthetic Clinic and Wellness Center has the right to take photos/ videos before, during and after treatment. These may be used for promotion purposes across all social media platforms.')
                                ->statePath('consent_waiver_form_data.acknowledge_right_to_record')
                                ->default($existingForm['acknowledge_right_to_record'] ?? false)
                                ->required(),
                        ]),

                    Forms\Components\Section::make('Signature')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\DatePicker::make('signature_date')
                                        ->label('Date:')
                                        ->statePath('consent_waiver_form_data.signature_date')
                                        ->default($existingForm['signature_date'] ?? now())
                                        ->required()
                                        ->native(false),
                                    Forms\Components\TextInput::make('signature_location')
                                        ->label('Location:')
                                        ->statePath('consent_waiver_form_data.signature_location')
                                        ->default($existingForm['signature_location'] ?? 'Zone 1, San Jose, Iriga City')
                                        ->required(),
                                ]),
                            Forms\Components\Textarea::make('signature_data')
                                ->label('Digital Signature (Type your full name):')
                                ->statePath('consent_waiver_form_data.signature_data')
                                ->default($existingForm['signature_data'] ?? '')
                                ->required()
                                ->placeholder('Type your full name as digital signature')
                                ->rows(2),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
