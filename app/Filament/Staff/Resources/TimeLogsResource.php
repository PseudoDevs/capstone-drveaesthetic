<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\TimeLogsResource\Pages;
use App\Filament\Staff\Resources\TimeLogsResource\RelationManagers;
use App\Models\TimeLogs;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TimeLogsResource extends Resource
{
    protected static ?string $model = TimeLogs::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $label = 'Time Log';

    protected static ?string $pluralLabel = 'Time Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id()),

                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(today()),

                Forms\Components\DateTimePicker::make('clock_in')
                    ->required()
                    ->seconds(false),

                Forms\Components\DateTimePicker::make('clock_out')
                    ->seconds(false)
                    ->afterOrEqual('clock_in'),

                Forms\Components\TextInput::make('total_hours')
                    ->numeric()
                    ->step(0.01)
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('clock_in')
                    ->dateTime('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('clock_out')
                    ->dateTime('H:i')
                    ->placeholder('Not clocked out')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_hours')
                    ->numeric(2)
                    ->suffix(' hrs')
                    ->placeholder('--')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (TimeLogs $record) => $record->isActive() ? 'Active' : 'Completed')
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Completed' => 'gray',
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->placeholder('From Date'),
                        Forms\Components\DatePicker::make('date_to')
                            ->placeholder('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeLogs::route('/'),
            'create' => Pages\CreateTimeLogs::route('/create'),
            'edit' => Pages\EditTimeLogs::route('/{record}/edit'),
        ];
    }
}
