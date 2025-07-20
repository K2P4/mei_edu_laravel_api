<?php

namespace App\Enum;

enum StatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';

     case REJECTED = 'rejected';
    case RESCHEDULED = 'rescheduled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED  => 'Rejected',
            self::RESCHEDULED => 'rescheduled',
        };
    }
}
