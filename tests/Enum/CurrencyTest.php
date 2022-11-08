<?php

declare(strict_types=1);

namespace App\Tests\Enum;

use App\Enum\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /** @test */
    public function should_return_currency_cases_without_default_language()
    {
        $this->assertFalse(in_array(Currency::POLISH_ZLOTY, Currency::getFormChoices()));
    }

    /** @test */
    public function should_return_currency_by_code()
    {
        $this->assertEquals(Currency::POUND_STERLING, Currency::from('gbp'));
    }
}