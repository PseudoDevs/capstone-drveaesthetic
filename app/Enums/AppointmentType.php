<?php

namespace App\Enums;

enum AppointmentType: string
{
    case ONLINE = 'ONLINE';
    case WALK_IN = 'WALK_IN';

    public function label(): string
    {
        return match($this) {
            self::ONLINE => 'Online',
            self::WALK_IN => 'Walk-in',
        };
    }
}
