<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Filament\Resources\ContactSubmissionResource\RelationManagers;
use App\Models\ContactSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contact Messages';

    protected static ?string $modelLabel = 'Contact Message';

    protected static ?string $pluralModelLabel = 'Contact Messages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_no')
                    ->label('Phone Number')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('subject')
                    ->label('Subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->label('Message')
                    ->required()
                    ->maxLength(5000)
                    ->rows(4),
                Forms\Components\Toggle::make('is_read')
                    ->label('Mark as Read')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_no')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All Messages')
                    ->trueLabel('Read Messages')
                    ->falseLabel('Unread Messages'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Date Range'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth('lg'),
                Tables\Actions\Action::make('mark_as_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_read)
                    ->action(fn ($record) => $record->update(['is_read' => true]))
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Read')
                    ->modalDescription('Are you sure you want to mark this message as read?'),
                Tables\Actions\Action::make('mark_as_unread')
                    ->label('Mark as Unread')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_read)
                    ->action(fn ($record) => $record->update(['is_read' => false]))
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Unread')
                    ->modalDescription('Are you sure you want to mark this message as unread?'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_as_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_read' => true])))
                        ->requiresConfirmation()
                        ->modalHeading('Mark Messages as Read')
                        ->modalDescription('Are you sure you want to mark all selected messages as read?'),
                    Tables\Actions\BulkAction::make('mark_as_unread')
                        ->label('Mark as Unread')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_read' => false])))
                        ->requiresConfirmation()
                        ->modalHeading('Mark Messages as Unread')
                        ->modalDescription('Are you sure you want to mark all selected messages as unread?'),
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
            'index' => Pages\ListContactSubmissions::route('/'),
            'create' => Pages\CreateContactSubmission::route('/create'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
