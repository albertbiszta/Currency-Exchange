<?php

namespace App\DoctrineType;

use App\Enum\PaymentType;

class PaymentTypeType extends EnumType
{
    public const NAME = 'paymentTypeEnum';

    public static function getClass(): string
    {
        return PaymentType::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}