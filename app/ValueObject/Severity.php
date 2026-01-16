<?php

declare(strict_types=1);

namespace App\ValueObject;

enum Severity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case WARNING = 'warning';

    public function getLabel(): string
    {
        return match($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::WARNING => 'Warning',
        };
    }
}
