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
        return array_filter(self::cases(), fn($currency) => !$currency->isDefault());
    }

    public function getCode(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return match ($this) {
            self::EURO => 'Euro',
            self::POLISH_ZLOTY => 'Polish Zloty',
            self::POUND_STERLING => 'Pound Sterling',
            self::SWISS_FRANC => 'Swiss Franc',
            self::US_DOLLAR => 'U.S. Dollar',
        };
    }

    public function getNameWithAmount(float $amount): string
    {
        return $amount . ' ' . $this->getName();
    }

    public function isDefault(): bool
    {
        return $this === self::POLISH_ZLOTY;
    }

    private static function getNames(): array
    {
        return array_map(fn(self $currency) => $currency->getName(), self::cases());
    }

    private static function getCodes(): array
    {
        return array_map(fn(self $currency) => $currency->value, self::cases());
    }
}
