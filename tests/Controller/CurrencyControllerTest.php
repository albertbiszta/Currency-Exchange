<?php

namespace App\Tests\Controller;

use App\Enum\Currency;
use App\Service\CurrencyService;

class CurrencyControllerTest extends ControllerTestCase
{
    public function testShouldRedirectToHomepageWhenCurrencySlugIsInvalidInChartRoute()
    {
        $this->requestGet('/currency/chart/invalid-slug');
        $this->assertResponseRedirects('/');

        $this->requestGet('/currency/chart/polish-zloty');
        $this->assertResponseRedirects('/');
    }

    public function testShouldRedirectToChartWithTheNumberOfDaysSpecifiedAsLimitIfTheSelectedNumberIsTooHigh()
    {
        $slug = Currency::US_DOLLAR->getSlug();
        $this->requestGet("/currency/chart/$slug/days/99");
        $this->assertResponseRedirects("/currency/chart/$slug/days/" . CurrencyService::LIMIT_OF_NUMBER_OF_DAYS_ON_CHART);
    }

    public function testShouldRedirectChangeCurrencyCodeToSlug()
    {
        $this->requestGet('/currency/chart/usd');
        $this->assertResponseRedirects('/currency/chart/us-dollar');
    }
}