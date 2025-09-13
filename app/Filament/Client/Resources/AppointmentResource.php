<?php

namespace App\Filament\Client\Resources;

use App\FormType;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Client\Resources\AppointmentResource\Pages;
use App\Filament\Client\Resources\AppointmentResource\RelationManagers;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'My Appointments';

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?string $pluralModelLabel = 'Appointments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book Your Appointment')
                    ->description('Please fill out the following information to schedule your appointment')
                    ->schema([
                        Forms\Components\Hidden::make('client_id')
                            ->default(fn () => Filament::auth()->id()),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('service_id')
                                    ->label('Service')
                                    ->relationship('service', 'service_name')
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Choose the service you need'),
                                Forms\Components\Select::make('staff_id')
                                    ->label('Preferred Staff')
                                    ->relationship('staff', 'name', fn (Builder $query) =>
                                        $query->where('role', 'staff')
                                    )
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Select your preferred staff member'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('appointment_date')
                                    ->label('Appointment Date')
                                    ->native(false)
                                    ->required()
                                    ->minDate(now()->addDay())
                                    ->placeholder('Choose your appointment date'),
                                Forms\Components\TimePicker::make('appointment_time')
                                    ->label('Appointment Time')
                                    ->required()
                                    ->seconds(false)
                                    ->placeholder('Select your preferred time'),
                            ]),
                        Forms\Components\Select::make('form_type')
                            ->label('Select Form Type First')
                            ->options(FormType::getOptions())
                            ->native(false)
                            ->required()
                            ->placeholder('What type of appointment is this?')
                            ->helperText('Select the form type to see the medical form fields that you will complete along with this appointment.')
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('form_fields_visible', true))
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('form_fields_visible')
                            ->default(false),
                        Forms\Components\Hidden::make('status')
                            ->default('PENDING'),
                        Forms\Components\Hidden::make('is_rescheduled')
                            ->default(false),
                        Forms\Components\Hidden::make('is_paid')
                            ->default(false),
                        Forms\Components\Hidden::make('form_completed')
                            ->default(false),
                    ])
                    ->columns(1),

                // Dynamic form fields based on form_type selection
                Forms\Components\Section::make(fn (Forms\Get $get): string =>
                    $get('form_type') ? FormType::from($get('form_type'))->getLabel() : 'Medical Form'
                )
                    ->description('Dr. Ve Aesthetic Clinic and Wellness Center - Zone 1, San Jose, Iriga City, Camarines Sur')
                    ->schema(fn (Forms\Get $get): array =>
                        $get('form_type') ? static::getDynamicFormFields($get('form_type')) : []
                    )
                    ->visible(fn (Forms\Get $get): bool => (bool) $get('form_type'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('client_id', Filament::auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('service.service_name')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Staff')
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'SCHEDULED' => 'info',
                        'COMPLETED' => 'success',
                        'CANCELLED' => 'danger',
                        'DECLINED' => 'danger',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('form_type')
                    ->label('Appointment Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string =>
                        $state ? FormType::from($state)->getLabel() : 'No Form Type'
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'SCHEDULED' => 'Scheduled',
                        'COMPLETED' => 'Completed',
                        'CANCELLED' => 'Cancelled',
                        'DECLINED' => 'Declined',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('fillForm')
                    ->label('Fill Form')
                    ->icon('heroicon-m-document-text')
                    ->color('warning')
                    ->visible(fn (Appointment $record): bool =>
                        !$record->form_completed && $record->form_type &&
                        in_array($record->status, ['PENDING', 'SCHEDULED'])
                    )
                    ->slideOver()
                    ->modalWidth('5xl')
                    ->modalHeading(fn (Appointment $record): string =>
                        FormType::from($record->form_type)->getLabel() . ' - ' . $record->service->service_name
                    )
                    ->form(fn (Appointment $record) => static::getFormSchema($record))
                    ->action(function (Appointment $record, array $data): void {
                        static::handleFormSubmission($record, $data);
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Appointment $record): bool =>
                        in_array($record->status, ['PENDING'])
                    )
                    ->slideOver()
                    ->modalWidth('5xl')
                    ->fillForm(function (Appointment $record): array {
                        $data = $record->toArray();

                        // Merge form data if exists
                        if ($record->medical_form_data) {
                            $data['medical_form_data'] = $record->medical_form_data;
                        }
                        if ($record->consent_waiver_form_data) {
                            $data['consent_waiver_form_data'] = $record->consent_waiver_form_data;
                        }

                        return $data;
                    }),
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth('4xl'),
            ])
            ->bulkActions([
                // Remove bulk actions for clients
            ])
            ->defaultSort('appointment_date', 'desc')
            ->headerActions([
                // Remove create action button for clients
                CreateAction::make()
                    ->label('Create Appointment')
                    ->icon('heroicon-o-plus')
                    ->slideOver()
                    ->modalWidth('5xl')
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Appointment Details')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('service.service_name')
                                    ->label('Service'),
                                Infolists\Components\TextEntry::make('staff.name')
                                    ->label('Staff Member'),
                                Infolists\Components\TextEntry::make('appointment_date')
                                    ->label('Date')
                                    ->date(),
                                Infolists\Components\TextEntry::make('appointment_time')
                                    ->label('Time'),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'PENDING' => 'warning',
                                        'SCHEDULED' => 'info',
                                        'COMPLETED' => 'success',
                                        'CANCELLED' => 'danger',
                                        'DECLINED' => 'danger',
                                        default => 'gray'
                                    }),
                                Infolists\Components\TextEntry::make('form_type')
                                    ->label('Form Type')
                                    ->formatStateUsing(fn (?string $state): string =>
                                        $state ? FormType::from($state)->getLabel() : 'No Form Type'
                                    )
                                    ->badge()
                                    ->color(fn (?string $state): string => match ($state) {
                                        'medical_information' => 'info',
                                        'consent_waiver' => 'warning',
                                        default => 'gray'
                                    }),
                                Infolists\Components\IconEntry::make('form_completed')
                                    ->label('Form Completed')
                                    ->boolean(),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Booked On')
                                    ->dateTime(),
                            ]),
                    ]),

                Infolists\Components\Section::make('Form Data')
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema(fn (Appointment $record): array =>
                                $record->form_type === 'medical_information'
                                    ? static::getMedicalFormInfolist($record)
                                    : static::getConsentWaiverFormInfolist($record)
                            )
                    ])
                    ->visible(fn (Appointment $record): bool =>
                        $record->form_completed &&
                        ($record->medical_form_data || $record->consent_waiver_form_data)
                    ),
            ]);
    }

    public static function getMedicalFormInfolist(Appointment $record): array
    {
        $formData = $record->medical_form_data ?? [];

        return [
            Infolists\Components\Section::make('Patient Information')
                ->schema([
                    Infolists\Components\Grid::make(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('patient_name')
                                ->label('Name')
                                ->state($formData['patient_name'] ?? 'N/A'),
                            Infolists\Components\TextEntry::make('date')
                                ->label('Date')
                                ->state($formData['date'] ?? 'N/A')
                                ->date(),
                            Infolists\Components\TextEntry::make('address')
                                ->label('Address')
                                ->state($formData['address'] ?? 'N/A')
                                ->columnSpanFull(),
                            Infolists\Components\TextEntry::make('procedure')
                                ->label('Procedure')
                                ->state($formData['procedure'] ?? 'N/A')
                                ->columnSpanFull(),
                        ]),
                ]),

            Infolists\Components\Section::make('Medical History')
                ->schema([
                    Infolists\Components\Fieldset::make('Past Medical Conditions')
                        ->schema([
                            Infolists\Components\Grid::make(4)
                                ->schema([
                                    Infolists\Components\IconEntry::make('allergy')
                                        ->label('Allergy')
                                        ->state($formData['allergy'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('diabetes')
                                        ->label('Diabetes')
                                        ->state($formData['diabetes'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('thyroid_diseases')
                                        ->label('Thyroid Diseases')
                                        ->state($formData['thyroid_diseases'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('stroke')
                                        ->label('Stroke')
                                        ->state($formData['stroke'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('heart_diseases')
                                        ->label('Heart Diseases')
                                        ->state($formData['heart_diseases'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('kidney_diseases')
                                        ->label('Kidney Diseases')
                                        ->state($formData['kidney_diseases'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('hypertension')
                                        ->label('Hypertension')
                                        ->state($formData['hypertension'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('asthma')
                                        ->label('Asthma')
                                        ->state($formData['asthma'] ?? false)
                                        ->boolean(),
                                ]),
                        ]),
                    Infolists\Components\Fieldset::make('Current Status')
                        ->schema([
                            Infolists\Components\Grid::make(4)
                                ->schema([
                                    Infolists\Components\IconEntry::make('pregnant')
                                        ->label('Pregnant')
                                        ->state($formData['pregnant'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('lactating')
                                        ->label('Lactating')
                                        ->state($formData['lactating'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('smoker')
                                        ->label('Smoker')
                                        ->state($formData['smoker'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('alcoholic_drinker')
                                        ->label('Alcoholic Drinker')
                                        ->state($formData['alcoholic_drinker'] ?? false)
                                        ->boolean(),
                                ]),
                        ]),
                    Infolists\Components\TextEntry::make('medical_history_others')
                        ->label('Other Medical Conditions')
                        ->state($formData['medical_history_others'] ?? 'None specified')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('maintenance_medications')
                        ->label('Maintenance Medications')
                        ->state($formData['maintenance_medications'] ?? 'None specified')
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function getConsentWaiverFormInfolist(Appointment $record): array
    {
        $formData = $record->consent_waiver_form_data ?? [];

        return [
            Infolists\Components\Section::make('Patient Information')
                ->schema([
                    Infolists\Components\Grid::make(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('patient_name')
                                ->label('Name')
                                ->state($formData['patient_name'] ?? 'N/A'),
                            Infolists\Components\TextEntry::make('age')
                                ->label('Age')
                                ->state($formData['age'] ?? 'N/A')
                                ->suffix(' years old'),
                            Infolists\Components\TextEntry::make('civil_status')
                                ->label('Civil Status')
                                ->state($formData['civil_status'] ?? 'N/A')
                                ->badge(),
                            Infolists\Components\TextEntry::make('residence')
                                ->label('Residence')
                                ->state($formData['residence'] ?? 'N/A')
                                ->columnSpanFull(),
                        ]),
                ]),

            Infolists\Components\Section::make('Services & Agreements')
                ->schema([
                    Infolists\Components\TextEntry::make('services_availed')
                        ->label('Services Availed')
                        ->state($formData['services_availed'] ?? 'N/A')
                        ->columnSpanFull(),

                    Infolists\Components\Fieldset::make('Consent Confirmations')
                        ->schema([
                            Infolists\Components\Grid::make(1)
                                ->schema([
                                    Infolists\Components\IconEntry::make('interviewed_advised_counselled')
                                        ->label('Medical history and health status discussed')
                                        ->state($formData['interviewed_advised_counselled'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('hold_clinic_free_from_liabilities')
                                        ->label('Liability waiver agreed')
                                        ->state($formData['hold_clinic_free_from_liabilities'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('read_understood_consent')
                                        ->label('Terms read and understood')
                                        ->state($formData['read_understood_consent'] ?? false)
                                        ->boolean(),
                                    Infolists\Components\IconEntry::make('acknowledge_right_to_record')
                                        ->label('Photo/video recording consent')
                                        ->state($formData['acknowledge_right_to_record'] ?? false)
                                        ->boolean(),
                                ]),
                        ]),
                ]),

            Infolists\Components\Section::make('Digital Signature')
                ->schema([
                    Infolists\Components\Grid::make(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('signature_date')
                                ->label('Signature Date')
                                ->state($formData['signature_date'] ?? 'N/A')
                                ->date(),
                            Infolists\Components\TextEntry::make('signature_location')
                                ->label('Location')
                                ->state($formData['signature_location'] ?? 'N/A'),
                            Infolists\Components\TextEntry::make('signature_data')
                                ->label('Digital Signature')
                                ->state($formData['signature_data'] ?? 'N/A')
                                ->columnSpanFull()
                                ->copyable(),
                        ]),
                ]),
        ];
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

    public static function getFormSchema(Appointment $appointment): array
    {
        return match ($appointment->form_type) {
            'medical_information' => static::getMedicalFormSchema($appointment),
            'consent_waiver' => static::getConsentWaiverFormSchema($appointment),
            default => [],
        };
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
                                ->default($existingForm['patient_name'] ?? Filament::auth()->user()->name)
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
                        ->default($existingForm['address'] ?? '')
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
                                ->default($existingForm['patient_name'] ?? Filament::auth()->user()->name)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('age')
                                ->label('Age:')
                                ->statePath('consent_waiver_form_data.age')
                                ->default($existingForm['age'] ?? '')
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
                                ->default($existingForm['residence'] ?? '')
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

    public static function getDynamicFormFields(string $formType): array
    {
        return match ($formType) {
            'medical_information' => static::getMedicalFormFieldsForCreation(),
            'consent_waiver' => static::getConsentWaiverFormFieldsForCreation(),
            default => [],
        };
    }

    public static function getMedicalFormFieldsForCreation(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('patient_name')
                        ->label('Name:')
                        ->statePath('medical_form_data.patient_name')
                        ->default(fn () => Filament::auth()->user()->name)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('date')
                        ->label('Date:')
                        ->statePath('medical_form_data.date')
                        ->default(now()->format('Y-m-d'))
                        ->required()
                ]),
            Forms\Components\Textarea::make('address')
                ->label('Address:')
                ->statePath('medical_form_data.address')
                ->required()
                ->rows(2)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('procedure')
                ->label('Procedure:')
                ->statePath('medical_form_data.procedure')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Section::make('Past Medical History:')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Checkbox::make('allergy')
                                ->label('Allergy')
                                ->statePath('medical_form_data.allergy'),
                            Forms\Components\Checkbox::make('diabetes')
                                ->label('Diabetes')
                                ->statePath('medical_form_data.diabetes'),
                            Forms\Components\Checkbox::make('thyroid_diseases')
                                ->label('Thyroid diseases')
                                ->statePath('medical_form_data.thyroid_diseases'),
                            Forms\Components\Checkbox::make('stroke')
                                ->label('Stroke')
                                ->statePath('medical_form_data.stroke'),
                            Forms\Components\Checkbox::make('heart_diseases')
                                ->label('Heart Diseases')
                                ->statePath('medical_form_data.heart_diseases'),
                            Forms\Components\Checkbox::make('kidney_diseases')
                                ->label('Kidney Diseases')
                                ->statePath('medical_form_data.kidney_diseases'),
                            Forms\Components\Checkbox::make('hypertension')
                                ->label('Hypertension')
                                ->statePath('medical_form_data.hypertension'),
                            Forms\Components\Checkbox::make('asthma')
                                ->label('Asthma')
                                ->statePath('medical_form_data.asthma'),
                        ]),
                    Forms\Components\Textarea::make('medical_history_others')
                        ->label('Others:')
                        ->statePath('medical_form_data.medical_history_others')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Are you?')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Checkbox::make('pregnant')
                                ->label('Pregnant')
                                ->statePath('medical_form_data.pregnant'),
                            Forms\Components\Checkbox::make('lactating')
                                ->label('Lactating')
                                ->statePath('medical_form_data.lactating'),
                            Forms\Components\Checkbox::make('smoker')
                                ->label('Smoker')
                                ->statePath('medical_form_data.smoker'),
                            Forms\Components\Checkbox::make('alcoholic_drinker')
                                ->label('Alcoholic Drinker')
                                ->statePath('medical_form_data.alcoholic_drinker'),
                        ]),
                ]),

            Forms\Components\Section::make('Maintenance Medications:')
                ->schema([
                    Forms\Components\Textarea::make('maintenance_medications')
                        ->label('')
                        ->statePath('medical_form_data.maintenance_medications')
                        ->rows(4)
                        ->columnSpanFull()
                        ->placeholder('List all maintenance medications you are currently taking...'),
                ]),
        ];
    }

    public static function getConsentWaiverFormFieldsForCreation(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('patient_name')
                        ->label('I,')
                        ->statePath('consent_waiver_form_data.patient_name')
                        ->default(fn () => Filament::auth()->user()->name)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('age')
                        ->label('of legal age,')
                        ->statePath('consent_waiver_form_data.age')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(150)
                        ->suffix('years old'),
                ]),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('civil_status')
                        ->label('single/married and a resident of')
                        ->statePath('consent_waiver_form_data.civil_status')
                        ->options([
                            'single' => 'Single',
                            'married' => 'Married',
                        ])
                        ->required()
                        ->native(false),
                    Forms\Components\Textarea::make('residence')
                        ->label('do hereby agree and undertake the following, to wit:')
                        ->statePath('consent_waiver_form_data.residence')
                        ->required()
                        ->rows(2)
                        ->placeholder('Enter your complete address'),
                ]),

            Forms\Components\Section::make('Agreement and Consent')
                ->description('Please read each statement carefully and check all that apply')
                ->schema([
                    Forms\Components\Checkbox::make('interviewed_advised_counselled')
                        ->label('1. That I have been interviewed, advised and counselled regarding my medical history and health status.')
                        ->statePath('consent_waiver_form_data.interviewed_advised_counselled')
                        ->required(),
                    Forms\Components\Textarea::make('services_availed')
                        ->label('2. I availed the service/s of')
                        ->statePath('consent_waiver_form_data.services_availed')
                        ->required()
                        ->rows(2)
                        ->placeholder('Specify the services you will receive'),
                    Forms\Components\Checkbox::make('hold_clinic_free_from_liabilities')
                        ->label('3. I hold Dr. Ve Aesthetic Clinic and Wellness Center and its employees, officers, medical practitioner / nurse/aesthetician free from all liabilities and damages resulting to claims, suits and action for injuries, caused by or arising out from any untoward events, intentional/ unintentional acts regarding my procedure/s.')
                        ->statePath('consent_waiver_form_data.hold_clinic_free_from_liabilities')
                        ->required(),
                    Forms\Components\Checkbox::make('read_understood_consent')
                        ->label('4. That I have read and understood the foregoing and acknowledge my consent to this waiver by signing hereof.')
                        ->statePath('consent_waiver_form_data.read_understood_consent')
                        ->required(),
                    Forms\Components\Checkbox::make('acknowledge_right_to_record')
                        ->label('5. I acknowledge that Dr. Ve Aesthetic Clinic and Wellness Center has the right to take photos/ videos before, during and after treatment. These may be used for promotion purposes across all social media platforms.')
                        ->statePath('consent_waiver_form_data.acknowledge_right_to_record')
                        ->required(),
                ]),

            Forms\Components\Section::make('Signature')
                ->description('In witness whereof, I have hereunto affixed my signature this ___ day of 2024 at Zone 1, San Jose, Iriga City')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('signature_date')
                                ->label('Date:')
                                ->statePath('consent_waiver_form_data.signature_date')
                                ->default(fn () => now())
                                ->required()
                                ->native(false),
                            Forms\Components\TextInput::make('signature_location')
                                ->label('Location:')
                                ->statePath('consent_waiver_form_data.signature_location')
                                ->default('Zone 1, San Jose, Iriga City')
                                ->required(),
                        ]),
                    Forms\Components\Textarea::make('signature_data')
                        ->label('Signature (Type your full name as digital signature):')
                        ->statePath('consent_waiver_form_data.signature_data')
                        ->required()
                        ->placeholder('Type your full name as digital signature')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function handleFormSubmission(Appointment $appointment, array $data): void
    {
        if ($appointment->form_type === 'medical_information') {
            $appointment->update([
                'medical_form_data' => $data['medical_form_data'] ?? $data,
                'form_completed' => true
            ]);
            $formType = 'Medical Information';
        } else {
            $appointment->update([
                'consent_waiver_form_data' => $data['consent_waiver_form_data'] ?? $data,
                'form_completed' => true
            ]);
            $formType = 'Consent & Waiver';
        }

        \Filament\Notifications\Notification::make()
            ->title($formType . ' form submitted successfully!')
            ->success()
            ->send();
    }

    public static function handleCreateFormSubmission(array $data): Appointment
    {
        // Ensure date field has a value
        if (empty($data['date'])) {
            $data['date'] = now()->format('Y-m-d');
        }

        // Extract form data
        $formFields = ['patient_name', 'date', 'address', 'procedure', 'allergy', 'diabetes', 'pregnant', 'maintenance_medications'];
        $formData = [];
        foreach ($formFields as $field) {
            if (isset($data[$field])) {
                $formData[$field] = $data[$field];
            }
        }

        // Create appointment with form data
        $appointmentData = [
            'client_id' => $data['client_id'],
            'service_id' => $data['service_id'],
            'staff_id' => $data['staff_id'],
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'form_type' => $data['form_type'],
            'status' => $data['status'] ?? 'PENDING',
            'is_rescheduled' => $data['is_rescheduled'] ?? false,
            'is_paid' => $data['is_paid'] ?? false,
            'form_completed' => true,
            'medical_form_data' => $data['form_type'] === 'medical_information' ? $formData : null,
            'consent_waiver_form_data' => $data['form_type'] === 'consent_waiver' ? $formData : null,
        ];

        return Appointment::create($appointmentData);
    }
}
