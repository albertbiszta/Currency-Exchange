<?php

declare(strict_types=1);

namespace App\Tests\Enum;

use App\Enum\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testShouldReturnCurrencyCasesWithoutDefaultLanguage()
    {
        $this->assertFalse(in_array(Currency::POLISH_ZLOTY, Currency::getChoices()));
    }

    public function testShouldReturnCurrencyByCode()
    {
        $this->assertEquals(Currency::POUND_STERLING, Currency::tryFrom('gbp'));
        $this->assertEquals('', Currency::tryFrom('sss'));
    }
}