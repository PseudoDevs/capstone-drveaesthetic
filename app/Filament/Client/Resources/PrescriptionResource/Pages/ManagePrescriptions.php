<?php

namespace App\Filament\Client\Resources\PrescriptionResource\Pages;

use App\Filament\Client\Resources\PrescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePrescriptions extends ManageRecords
{
    protected static string $resource = PrescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Clients cannot create prescriptions
        ];
    }
}

