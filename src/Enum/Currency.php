<?php

declare(strict_types=1);

namespace App\Enum;

use App\Exception\CurrencyException;

enum Currency: string
{
    case EURO = 'eur';
    case POLISH_ZLOTY = 'pln';
    case POUND_STERLING = 'gbp';
    case SWISS_FRANC = 'chf';
    case US_DOLLAR = 'usd';

    /**
     * @throws \App\Exception\CurrencyException
     */
    public static function getBySlug(string $slug): ?self
    {
        $matches = array_filter(self::cases(), fn(self $currency) => ($currency->getSlug() === $slug) && !$currency->isMainCurrency());
        if (!$matches) {
           throw new CurrencyException('Incorrect currency slug');
        }

        return reset($matches);
    }

    public static function getChoices(): array
    {
        return array_filter(self::cases(), fn(self $currency) => !$currency->isMainCurrency());
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

    public function getSlug(): string
    {
        return str_replace([' ', '.'], ['-', ''], mb_strtolower($this->getName()));
    }

    public function getNameWithAmount(float $amount): string
    {
        return $amount . ' ' . $this->getName();
    }

    public function isMainCurrency(): bool
    {
        return $this === self::POLISH_ZLOTY;
    }
}
