<?php

namespace App\Filament\Staff\Resources\ContactSubmissionResource\Pages;

use App\Filament\Staff\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
