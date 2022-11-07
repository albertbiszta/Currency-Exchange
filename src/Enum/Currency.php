<?php

namespace App\Enum;

enum Currency: string
{
    case EURO = 'eur';
    case POLISH_ZLOTY = 'pln';
    case POUND_STERLING = 'gbp';
    case SWISS_FRANC = 'chf';
    case US_DOLLAR = 'usd';

    public static function getFormChoices(): array
    {
        return array_combine(self::getNames(), self::getCodes());
    }

    public function getCode(): string
    {
        return $this->value;
    }

    public function getFullName(): string
    {
        return match ($this) {
            self::EURO => 'Euro',
            self::POLISH_ZLOTY => 'Polish Zloty',
            self::POUND_STERLING => 'Pound Sterling',
            self::SWISS_FRANC => 'Swiss Franc',
            self::US_DOLLAR => 'U.S. Dollar',
        };
    }

    private static function getNames(): array
    {
        return array_map(fn(self $currency) => $currency->getFullName(), self::cases());
    }

    private static function getCodes(): array
    {
        return array_map(fn(self $currency) => $currency->value, self::cases());
    }
}
