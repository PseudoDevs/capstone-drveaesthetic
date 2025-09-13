<?php

namespace App\Filament\Resources\ClinicServiceResource\Pages;

use App\Filament\Resources\ClinicServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinicService extends EditRecord
{
    protected static string $resource = ClinicServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
