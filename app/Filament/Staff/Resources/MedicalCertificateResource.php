<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\MedicalCertificateResource\Pages;
use App\Filament\Staff\Resources\MedicalCertificateResource\RelationManagers;
use App\Models\MedicalCertificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dompdf\Dompdf;
use Dompdf\Options;

class MedicalCertificateResource extends Resource
{
    protected static ?string $model = MedicalCertificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Appointment Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('issued_by')
                            ->label('Attending Medical Officer')
                            ->relationship('staff', 'name', fn (Builder $query) => $query->whereIn('role', ['Staff', 'Doctor', 'Admin']))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('client_id')
                            ->label('Patient/Client')
                            ->relationship('client', 'name', fn (Builder $query) => $query->where('role', 'Client'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                    ]),
                Forms\Components\Textarea::make('purpose')
                    ->label('Purpose of Medical Certificate')
                    ->placeholder('e.g., Employment clearance, Travel clearance, Insurance claim, etc.')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('certificate_number')
                            ->label('Certificate Number')
                            ->default(fn () => MedicalCertificate::generateCertificateNumber())
                            ->disabled()
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('active')
                            ->columnSpan(1),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('valid_from')
                            ->label('Valid From')
                            ->default(now())
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->default(now()->addDays(30))
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Patient/Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Purpose')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(function ($record) {
                        return $record->purpose;
                    }),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Medical Officer')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => ucfirst($state))
                    ->color(fn ($state): string => match($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Certificate Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Certificate Status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('issued_by')
                    ->label('Medical Officer')
                    ->relationship('staff', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Preview Certificate')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->slideOver()
                    ->modalWidth('4xl')
                    ->form([
                        Forms\Components\ViewField::make('certificate_preview')
                            ->label('')
                            ->view('medical-certificates.certificate-preview'),
                    ]),
                Tables\Actions\Action::make('print_certificate')
                    ->label('Print Certificate')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (MedicalCertificate $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo static::generateCertificatePDF($record);
                        }, "medical-certificate-{$record->client->name}-" . now()->format('Y-m-d') . ".pdf", [
                            'Content-Type' => 'application/pdf',
                        ]);
                    }),
                Tables\Actions\EditAction::make()->slideOver(),
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
            'index' => Pages\ListMedicalCertificates::route('/'),
        ];
    }

    public static function generateCertificatePDF(MedicalCertificate $certificate): string
    {
        // Load certificate with all related data
        $certificate->load(['client', 'staff']);

        // Generate HTML content
        $html = view('medical-certificates.certificate-template', [
            'certificate' => $certificate,
            'client' => $certificate->client,
            'staff' => $certificate->staff,
            'generatedDate' => now()->format('F d, Y'),
            'certificateNumber' => 'MC-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT),
        ])->render();

        // Create PDF
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
