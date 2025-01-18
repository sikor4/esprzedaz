<?php

namespace App\Enums;

enum PetStatus: string
{
    case AVAILABLE = 'available';
    case PENDING   = 'pending';
    case SOLD      = 'sold';

    public static function values(): array
    {
        return array_map(
            fn($case) => $case->value,
            self::cases()
        );
    }
}
