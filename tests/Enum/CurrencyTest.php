<?php

declare(strict_types=1);

namespace App\Tests\Enum;

use App\Enum\Currency;
use App\Exception\CurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testShouldReturnCorrectSlug()
    {
        $this->assertEquals('swiss-franc', Currency::SWISS_FRANC->getSlug());
        $this->assertEquals('us-dollar', Currency::US_DOLLAR->getSlug());
    }

    public function testShouldReturnCurrencyBySlug()
    {
        $this->assertEquals(Currency::US_DOLLAR, Currency::getBySlug('us-dollar'));
    }

    public function testShouldThrowExceptionForIncorrectCurrencySlug()
    {
        $this->expectException(CurrencyException::class);
        Currency::getBySlug('usdolla');
    }

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