<?php

namespace App\Tests\Entity;

use App\Entity\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{

    /** @test */
    public function should_return_array_with_currency_name_as_key_and_currency_code_as_value()
    {
        $expected = [
            'Euro' => 'eur',
            'Polish Zloty' => 'pln',
            'Pound Sterling' => 'gbp',
            'Swiss Franc' => 'chf',
            'U.S. Dollar' => 'usd',
        ];
        $this->assertEquals($expected, Currency::getFormChoices());

    }
}