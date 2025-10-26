<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\PrescriptionResource\Pages;
use App\Models\Prescription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationGroup = 'My Medical Records';
    
    protected static ?string $navigationLabel = 'My Prescriptions';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Client can only view, not edit
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => 
                $query->where('client_id', Filament::auth()->id())
                    ->with(['appointment.service', 'prescribedBy'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('prescribed_date')
                    ->label('Date Prescribed')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment.service.service_name')
                    ->label('Service/Treatment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medication_name')
                    ->label('Medication')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('dosage')
                    ->label('Dosage'),
                Tables\Columns\TextColumn::make('frequency')
                    ->label('How Often')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('prescribedBy.name')
                    ->label('Prescribed By')
                    ->sortable(),
            ])
            ->filters([
                // No filters needed for client view
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->infolist([
                        \Filament\Infolists\Components\Section::make('Prescription Details')
                            ->schema([
                                \Filament\Infolists\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('prescribed_date')
                                            ->label('Date Prescribed')
                                            ->date('F d, Y')
                                            ->icon('heroicon-m-calendar'),
                                        \Filament\Infolists\Components\TextEntry::make('appointment.service.service_name')
                                            ->label('Treatment/Service')
                                            ->icon('heroicon-m-beaker'),
                                    ]),
                            ]),
                        \Filament\Infolists\Components\Section::make('Medication Information')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('medication_name')
                                    ->label('Medication Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary'),
                                \Filament\Infolists\Components\Grid::make(3)
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('dosage')
                                            ->label('Dosage')
                                            ->icon('heroicon-m-beaker'),
                                        \Filament\Infolists\Components\TextEntry::make('frequency')
                                            ->label('Frequency')
                                            ->badge()
                                            ->color('info')
                                            ->icon('heroicon-m-clock'),
                                        \Filament\Infolists\Components\TextEntry::make('duration')
                                            ->label('Duration')
                                            ->badge()
                                            ->color('warning')
                                            ->icon('heroicon-m-calendar-days'),
                                    ]),
                                \Filament\Infolists\Components\TextEntry::make('instructions')
                                    ->label('Special Instructions')
                                    ->icon('heroicon-m-information-circle')
                                    ->placeholder('No special instructions')
                                    ->columnSpanFull(),
                                \Filament\Infolists\Components\TextEntry::make('notes')
                                    ->label('Doctor\'s Notes')
                                    ->icon('heroicon-m-clipboard-document-list')
                                    ->placeholder('No additional notes')
                                    ->columnSpanFull(),
                            ]),
                        \Filament\Infolists\Components\Section::make('Prescribed By')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('prescribedBy.name')
                                    ->label('Doctor/Staff')
                                    ->icon('heroicon-m-user'),
                            ]),
                    ]),
            ])
            ->bulkActions([
                // No bulk actions for clients
            ])
            ->defaultSort('prescribed_date', 'desc')
            ->emptyStateHeading('No Prescriptions Yet')
            ->emptyStateDescription('Your prescriptions will appear here after your appointments.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePrescriptions::route('/'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('client_id', Filament::auth()->id());
    }
    
    public static function canCreate(): bool
    {
        return false; // Clients cannot create prescriptions, only staff
    }
}









