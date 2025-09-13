<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\FeedbackResource\Pages;
use App\Filament\Staff\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Mokhosh\FilamentRating\Components\Rating;
use Mokhosh\FilamentRating\Columns\RatingColumn;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'Client Management';
    
    protected static ?int $navigationSort = 3;

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
                    ->disabled(),
                Forms\Components\Select::make('appointment_id')
                    ->label('Appointment')
                    ->relationship('appointment', 'id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(),
                Rating::make('rating')
                    ->label('Rating')
                    ->stars(5)
                    ->allowZero(false)
                    ->size('lg')
                    ->color('warning')
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('Comment/Review')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment.service.service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                RatingColumn::make('rating')
                    ->label('Rating')
                    ->size('sm')
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ]),
                SelectFilter::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn () => false), // Read-only for staff
            ])
            ->bulkActions([
                // No bulk actions for feedbacks
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
