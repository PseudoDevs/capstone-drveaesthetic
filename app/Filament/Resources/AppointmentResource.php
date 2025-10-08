<?php

namespace App\Filament\Resources;

use App\Enums\FormType;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Appointment Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'service_name')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->native(false)
                    ->required(),
                Forms\Components\TimePicker::make('appointment_time')
                    ->required(),
                Forms\Components\Toggle::make('is_rescheduled')
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'SCHEDULED' => 'Scheduled',
                        'ON-GOING' => 'On-Going',
                        'CANCELLED' => 'Cancelled',
                        'DECLINED' => 'Declined',
                        'RESCHEDULE' => 'Reschedule',
                        'COMPLETED' => 'Completed',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('form_type')
                    ->label('Appointment Type')
                    ->options(FormType::getOptions())
                    ->native(false)
                    ->nullable()
                    ->placeholder('Select appointment type (optional)'),
                Forms\Components\Toggle::make('form_completed')
                    ->default(false),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.service_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_rescheduled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('form_type')
                    ->label('Appointment Type')
                    ->badge()
                    ->formatStateUsing(fn (?FormType $state): string =>
                        $state ? $state->getLabel() : 'No Form Type'
                    )
                    ->color(fn (?FormType $state): string => match ($state) {
                        FormType::MEDICAL_INFORMATION => 'info',
                        FormType::CONSULTATION_FORM => 'warning',
                        default => 'gray'
                    }),
                Tables\Columns\IconColumn::make('form_completed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
        ];
    }
}
