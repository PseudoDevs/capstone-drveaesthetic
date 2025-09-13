<?php

namespace App\Filament\Staff\Resources\ClientReportResource\Pages;

use App\Filament\Staff\Resources\ClientReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientReport extends EditRecord
{
    protected static string $resource = ClientReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
