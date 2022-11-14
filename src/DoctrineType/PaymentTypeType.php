<?php

namespace App\DoctrineType;

use App\Enum\PaymentType;

final class PaymentTypeType extends EnumType
{
    public const NAME = 'paymentTypeEnum';

    public static function getClass(): string
    {
        return PaymentType::class;
    }
}