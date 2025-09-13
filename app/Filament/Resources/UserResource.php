<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dompdf\Dompdf;
use Dompdf\Options;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Staff' => 'Staff',
                        'Doctor' => 'Doctor',
                        'Client' => 'Client',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('email')
                    ->searchable()
                    ->color('gray'),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'Admin',
                        'success' => 'Doctor',
                        'warning' => 'Staff',
                        'primary' => 'Client',
                    ])
                    ->searchable(),
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
                Tables\Actions\Action::make('generateReport')
                    ->label('Generate Report')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->visible(fn (User $record) => $record->role === 'Client')
                    ->action(function (User $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo static::generateClientReportPDF($record);
                        }, "client-report-{$record->name}-" . now()->format('Y-m-d') . ".pdf", [
                            'Content-Type' => 'application/pdf',
                        ]);
                    }),
                Tables\Actions\EditAction::make()->slideOver(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add User')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->modalHeading('Create New User')
                    ->modalSubmitActionLabel('Create User')
                    ->createAnother(false)
                    ->slideOver(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function generateClientReportPDF(User $client): string
    {
        // Load client with all related data
        $client->load([
            'appointments.service',
            'appointments.staff',
            'feedbacks.appointment.service'
        ]);

        // Get appointments statistics
        $totalAppointments = $client->appointments->count();
        $completedAppointments = $client->appointments->where('status', 'COMPLETED')->count();
        $pendingAppointments = $client->appointments->where('status', 'PENDING')->count();
        $cancelledAppointments = $client->appointments->whereIn('status', ['CANCELLED', 'DECLINED'])->count();

        // Get services availed
        $servicesAvailed = $client->appointments
            ->where('status', 'COMPLETED')
            ->groupBy('service.service_name')
            ->map(function ($appointments) {
                return [
                    'service' => $appointments->first()->service->service_name,
                    'count' => $appointments->count(),
                    'total_paid' => $appointments->sum('service.price')
                ];
            })->values();

        // Calculate total revenue from this client
        $totalRevenue = $client->appointments
            ->where('status', 'COMPLETED')
            ->sum('service.price');

        // Generate HTML content
        $html = view('reports.client-report', compact(
            'client',
            'totalAppointments',
            'completedAppointments', 
            'pendingAppointments',
            'cancelledAppointments',
            'servicesAvailed',
            'totalRevenue'
        ))->render();

        // Create PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
