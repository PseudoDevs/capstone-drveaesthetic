<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\PrescriptionResource\Pages;
use App\Filament\Staff\Resources\PrescriptionResource\RelationManagers;
use App\Models\Prescription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    
    protected static ?string $navigationGroup = 'Client Management';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Prescription Details')
                    ->description('Add medication prescription for a completed appointment')
                    ->schema([
                        Forms\Components\Select::make('appointment_id')
                            ->label('Appointment')
                            ->options(function () {
                                return \App\Models\Appointment::query()
                                    ->where('status', 'completed')
                                    ->whereDoesntHave('prescriptions')
                                    ->with(['client', 'service'])
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(fn ($appointment) => [
                                        $appointment->id => "{$appointment->client->name} - {$appointment->service->service_name} ({$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time})"
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $appointment = \App\Models\Appointment::find($state);
                                    if ($appointment) {
                                        $set('client_id', $appointment->client_id);
                                        $set('prescribed_by', auth()->id());
                                        $set('prescribed_date', now()->format('Y-m-d'));
                                    }
                                }
                            }),
                        Forms\Components\Hidden::make('client_id'),
                        
                        Forms\Components\Select::make('prescribed_by')
                            ->label('Prescribed By')
                            ->options(function () {
                                return \App\Models\User::query()
                                    ->whereIn('role', ['Doctor', 'Staff'])
                                    ->pluck('name', 'id');
                            })
                            ->default(fn () => auth()->id())
                            ->searchable()
                            ->required()
                            ->helperText('Defaults to current user. Change if prescribing on behalf of another doctor.'),
                        
                        Forms\Components\Hidden::make('prescribed_date')
                            ->default(fn () => now()->format('Y-m-d')),
                    ]),
                    
                Forms\Components\Section::make('Medication Information')
                    ->schema([
                        Forms\Components\TextInput::make('medication_name')
                            ->label('Medication Name')
                            ->required()
                            ->placeholder('e.g., Ibuprofen, Amoxicillin')
                            ->maxLength(255),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('dosage')
                                    ->label('Dosage')
                                    ->required()
                                    ->placeholder('e.g., 500mg, 1 tablet')
                                    ->maxLength(255),
                                Forms\Components\Select::make('frequency')
                                    ->label('Frequency')
                                    ->options([
                                        'Once daily' => 'Once daily',
                                        'Twice daily' => 'Twice daily',
                                        'Three times daily' => 'Three times daily',
                                        'Four times daily' => 'Four times daily',
                                        'Every 4 hours' => 'Every 4 hours',
                                        'Every 6 hours' => 'Every 6 hours',
                                        'Every 8 hours' => 'Every 8 hours',
                                        'Every 12 hours' => 'Every 12 hours',
                                        'As needed' => 'As needed',
                                        'Before meals' => 'Before meals',
                                        'After meals' => 'After meals',
                                        'Before bedtime' => 'Before bedtime',
                                    ])
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('duration')
                                    ->label('Duration')
                                    ->options([
                                        '3 days' => '3 days',
                                        '5 days' => '5 days',
                                        '7 days' => '7 days',
                                        '10 days' => '10 days',
                                        '14 days' => '14 days',
                                        '21 days' => '21 days',
                                        '1 month' => '1 month',
                                        '2 months' => '2 months',
                                        '3 months' => '3 months',
                                        'As directed' => 'As directed',
                                        'Until finished' => 'Until finished',
                                    ])
                                    ->searchable()
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('instructions')
                            ->label('Special Instructions')
                            ->placeholder('e.g., Take with food, Avoid alcohol, etc.')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Doctor\'s Notes')
                            ->placeholder('Additional notes or observations')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['client', 'appointment.service', 'prescribedBy']))
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment.service.service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('medication_name')
                    ->label('Medication')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dosage')
                    ->label('Dosage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frequency')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('prescribedBy.name')
                    ->label('Prescribed By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prescribed_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('prescribed_by')
                    ->label('Prescribed By')
                    ->relationship('prescribedBy', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('staff.prescription.print', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('prescribed_date', 'desc');
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
            'index' => Pages\ListPrescriptions::route('/'),
            'create' => Pages\CreatePrescription::route('/create'),
            'edit' => Pages\EditPrescription::route('/{record}/edit'),
        ];
    }
}
