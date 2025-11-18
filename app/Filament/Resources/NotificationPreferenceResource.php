<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationPreferenceResource\Pages;
use App\Models\NotificationPreference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NotificationPreferenceResource extends Resource
{
    protected static ?string $model = NotificationPreference::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Section::make('Email Notifications')
                    ->schema([
                        Forms\Components\Toggle::make('email_notifications')
                            ->label('Enable Email Notifications')
                            ->default(true),

                        Forms\Components\Toggle::make('appointment_confirmations')
                            ->label('Appointment Confirmations')
                            ->default(true),

                        Forms\Components\Toggle::make('appointment_reminders_24h')
                            ->label('24-Hour Appointment Reminders')
                            ->default(true),

                        Forms\Components\Toggle::make('appointment_reminders_2h')
                            ->label('2-Hour Appointment Reminders')
                            ->default(true),

                        Forms\Components\Toggle::make('appointment_cancellations')
                            ->label('Appointment Cancellations')
                            ->default(true),

                        Forms\Components\Toggle::make('feedback_requests')
                            ->label('Feedback Requests')
                            ->default(true),

                        Forms\Components\Toggle::make('service_updates')
                            ->label('Service Updates')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Marketing Communications')
                    ->schema([
                        Forms\Components\Toggle::make('promotional_offers')
                            ->label('Promotional Offers')
                            ->default(false),

                        Forms\Components\Toggle::make('newsletter')
                            ->label('Newsletter')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\IconColumn::make('email_notifications')
                    ->label('Email Enabled')
                    ->boolean(),

                Tables\Columns\IconColumn::make('appointment_confirmations')
                    ->label('Confirmations')
                    ->boolean(),

                Tables\Columns\IconColumn::make('appointment_reminders_24h')
                    ->label('24h Reminders')
                    ->boolean(),

                Tables\Columns\IconColumn::make('appointment_reminders_2h')
                    ->label('2h Reminders')
                    ->boolean(),

                Tables\Columns\IconColumn::make('feedback_requests')
                    ->label('Feedback')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_notifications')
                    ->label('Email Notifications Enabled'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotificationPreferences::route('/'),
            'create' => Pages\CreateNotificationPreference::route('/create'),
            'edit' => Pages\EditNotificationPreference::route('/{record}/edit'),
        ];
    }
}
