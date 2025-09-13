<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingResource\Pages;
use App\Filament\Resources\TrainingResource\RelationManagers;
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

    protected static ?string $navigationGroup = 'Training & Guidelines';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Training Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Type/Category')
                    ->options([
                        'Safety Protocol' => 'Safety Protocol',
                        'Customer Service' => 'Customer Service',
                        'Technical Training' => 'Technical Training',
                        'Policy Guidelines' => 'Policy Guidelines',
                        'Equipment Usage' => 'Equipment Usage',
                        'Emergency Procedures' => 'Emergency Procedures',
                        'Quality Standards' => 'Quality Standards',
                        'Professional Development' => 'Professional Development',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->columnSpanFull()
                    ->label('Thumbnail Image')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('force')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('450')
                    ->directory('training-thumbnails')
                    ->visibility('public')
                    ->nullable()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('200')
                    ->uploadingMessage('Uploading image...')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                Forms\Components\RichEditor::make('description')
                    ->label('Training Content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(true)
                    ->helperText('Only published training materials will be visible to staff'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'danger' => 'Safety Protocol',
                        'success' => 'Customer Service',
                        'primary' => 'Technical Training',
                        'warning' => 'Policy Guidelines',
                        'info' => 'Equipment Usage',
                        'danger' => 'Emergency Procedures',
                        'success' => 'Quality Standards',
                        'secondary' => 'Professional Development',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\Filter::make('is_published')
                    ->label('Published Only')
                    ->query(fn (Builder $query): Builder => $query->where('is_published', true))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTrainings::route('/'),
        ];
    }
}
