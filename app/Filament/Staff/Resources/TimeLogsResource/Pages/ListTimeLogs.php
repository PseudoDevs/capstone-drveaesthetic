<?php

namespace App\Filament\Staff\Resources\TimeLogsResource\Pages;

use App\Filament\Staff\Resources\TimeLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimeLogs extends ListRecords
{
    protected static string $resource = TimeLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
