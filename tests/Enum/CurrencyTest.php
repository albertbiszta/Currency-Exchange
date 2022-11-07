<?php

namespace App\Tests\Enum;

use App\Enum\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /** @test */
    public function should_return_form_choices_with_fullname_as_key_and_code_as_value()
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

    /** @test */
    public function should_return_currency_by_code()
    {
        $this->assertEquals(Currency::POUND_STERLING, Currency::from('gbp'));
    }
}