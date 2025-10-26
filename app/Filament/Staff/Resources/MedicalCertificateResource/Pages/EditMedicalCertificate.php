<?php

namespace App\Filament\Staff\Resources\MedicalCertificateResource\Pages;

use App\Filament\Staff\Resources\MedicalCertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicalCertificate extends EditRecord
{
    protected static string $resource = MedicalCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
