<?php

namespace App\Filament\Staff\Resources\FeedbackResource\Pages;

use App\Filament\Staff\Resources\FeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedback extends CreateRecord
{
    protected static string $resource = FeedbackResource::class;
}
