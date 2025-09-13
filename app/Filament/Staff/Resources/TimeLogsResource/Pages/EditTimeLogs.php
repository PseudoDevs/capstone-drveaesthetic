<?php

namespace App\Filament\Staff\Resources\TimeLogsResource\Pages;

use App\Filament\Staff\Resources\TimeLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeLogs extends EditRecord
{
    protected static string $resource = TimeLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
