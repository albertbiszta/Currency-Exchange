<?php

namespace App\Entity;

class Currency
{
    public const EURO_SHORTNAME = 'eur';
    public const POLISH_ZLOTY_SHORTNAME = 'pln';
    public const POUND_STERLING_SHORTNAME = 'gbp';
    public const SWISS_FRANC_SHORTNAME = 'chf';
    public const US_DOLLAR_SHORTNAME = 'usd';

    public const CURRENCY_CHOICES = [
        'Euro' => self::EURO_SHORTNAME,
        'Polish Zloty' => self::POLISH_ZLOTY_SHORTNAME,
        'Pound Sterling' => self::POUND_STERLING_SHORTNAME,
        'Swiss Franc' => self::SWISS_FRANC_SHORTNAME,
        'U.S. Dollar' => self::US_DOLLAR_SHORTNAME,
    ];
}