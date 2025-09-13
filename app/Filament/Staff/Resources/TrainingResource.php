<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\TrainingResource\Pages;
use App\Filament\Staff\Resources\TrainingResource\RelationManagers;
use App\Models\Training;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingResource extends Resource
{
    protected static ?string $model = Training::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Training & Guidelines';
    
    protected static ?string $modelLabel = 'Training';
    
    protected static ?string $pluralModelLabel = 'Training & Guidelines';
    
    protected static ?string $navigationGroup = 'Resources';
    
    protected static ?int $navigationSort = 1;
    
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form for staff
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('thumbnail')
                        ->height(200)
                        ->width('100%')
                        ->defaultImageUrl('/images/training-placeholder.png'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->weight('bold')
                            ->size('lg'),
                        Tables\Columns\BadgeColumn::make('type')
                            ->colors([
                                'danger' => 'Safety Protocol',
                                'success' => 'Customer Service',
                                'primary' => 'Technical Training',
                                'warning' => 'Policy Guidelines',
                                'info' => 'Equipment Usage',
                                'danger' => 'Emergency Procedures',
                                'success' => 'Quality Standards',
                                'secondary' => 'Professional Development',
                            ]),
                        Tables\Columns\TextColumn::make('description')
                            ->html()
                            ->limit(100)
                            ->color('gray'),
                        Tables\Columns\TextColumn::make('created_at')
                            ->label('Published')
                            ->date()
                            ->color('gray')
                            ->size('sm'),
                    ])->space(2),
                ])
                ->space(3),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Safety Protocol' => 'Safety Protocol',
                        'Customer Service' => 'Customer Service',
                        'Technical Training' => 'Technical Training',
                        'Policy Guidelines' => 'Policy Guidelines',
                        'Equipment Usage' => 'Equipment Usage',
                        'Emergency Procedures' => 'Emergency Procedures',
                        'Quality Standards' => 'Quality Standards',
                        'Professional Development' => 'Professional Development',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Read More')
                    ->slideOver()
                    ->modalWidth('4xl')
                    ->infolist([
                        \Filament\Infolists\Components\ImageEntry::make('thumbnail')
                            ->height(200)
                            ->hiddenLabel(),
                        \Filament\Infolists\Components\TextEntry::make('title')
                            ->size('xl')
                            ->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Safety Protocol' => 'danger',
                                'Customer Service' => 'success',
                                'Technical Training' => 'primary',
                                'Policy Guidelines' => 'warning',
                                'Equipment Usage' => 'info',
                                'Emergency Procedures' => 'danger',
                                'Quality Standards' => 'success',
                                'Professional Development' => 'secondary',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->html()
                            ->prose()
                            ->hiddenLabel(),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Published Date')
                            ->dateTime(),
                    ]),
            ])
            ->bulkActions([
                // No bulk actions for staff
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([6, 12, 24, 48]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_published', true);
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
            'index' => Pages\ListTrainings::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function canEdit($record): bool
    {
        return false;
    }
    
    public static function canDelete($record): bool
    {
        return false;
    }
}