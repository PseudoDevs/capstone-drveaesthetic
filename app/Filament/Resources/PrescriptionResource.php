<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionResource\Pages;
use App\Filament\Resources\PrescriptionResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Prescription Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('appointment_id')
                                    ->label('Appointment')
                                    ->relationship('appointment', 'id')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                
                                Forms\Components\Select::make('client_id')
                                    ->label('Client')
                                    ->relationship('client', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                        
                        Forms\Components\Select::make('prescribed_by')
                            ->label('Prescribed By')
                            ->relationship('prescribedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                
                Forms\Components\Section::make('Medication Information')
                    ->schema([
                        Forms\Components\TextInput::make('medication_name')
                            ->label('Medication Name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('dosage')
                            ->label('Dosage')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., 500mg, 1 tablet'),
                        
                        Forms\Components\TextInput::make('frequency')
                            ->label('Frequency')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., Twice daily, Every 8 hours'),
                        
                        Forms\Components\TextInput::make('duration')
                            ->label('Duration')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., 7 days, 2 weeks'),
                        
                        Forms\Components\Textarea::make('instructions')
                            ->label('Instructions')
                            ->rows(3)
                            ->helperText('Special instructions for taking the medication'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->helperText('Additional notes or warnings'),
                        
                        Forms\Components\DatePicker::make('prescribed_date')
                            ->label('Prescribed Date')
                            ->default(now())
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('appointment.id')
                    ->label('Appointment ID')
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
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('prescribedBy.name')
                    ->label('Prescribed By')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('prescribed_date')
                    ->label('Prescribed Date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('prescribed_by')
                    ->relationship('prescribedBy', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('prescribed_date')
                    ->form([
                        Forms\Components\DatePicker::make('prescribed_from')
                            ->label('Prescribed From'),
                        Forms\Components\DatePicker::make('prescribed_until')
                            ->label('Prescribed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['prescribed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('prescribed_date', '>=', $date),
                            )
                            ->when(
                                $data['prescribed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('prescribed_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePrescriptions::route('/'),
        ];
    }
}
