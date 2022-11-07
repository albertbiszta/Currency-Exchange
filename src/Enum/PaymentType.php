<?php

namespace App\Enum;

enum PaymentType: int {
    case DEPOSIT = 0;
    case WITHDRAW = 1;

    public function getName(): string
    {
        return match($this) {
            self::DEPOSIT => 'deposit',
            self::WITHDRAW => 'withdraw',
        };
    }
}