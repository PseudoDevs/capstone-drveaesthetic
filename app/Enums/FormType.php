<?php

namespace App\Enums;

enum FormType: string
{
    case MEDICAL_INFORMATION = 'medical_information';
    case CONSULTATION_FORM = 'consultation_form';
    case TREATMENT_FORM = 'treatment_form';
    case FOLLOW_UP_FORM = 'follow_up_form';

    public function getLabel(): string
    {
        return match($this) {
            self::MEDICAL_INFORMATION => 'Medical Information',
            self::CONSULTATION_FORM => 'Consultation Form',
            self::TREATMENT_FORM => 'Treatment Form',
            self::FOLLOW_UP_FORM => 'Follow-up Form',
        };
    }

    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}