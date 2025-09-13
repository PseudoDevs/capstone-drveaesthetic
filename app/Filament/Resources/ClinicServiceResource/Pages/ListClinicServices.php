<?php

namespace App\Filament\Resources\ClinicServiceResource\Pages;

use App\Filament\Resources\ClinicServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinicServices extends ListRecords
{
    protected static string $resource = ClinicServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
