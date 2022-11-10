<?php

namespace App\Tests\Controller;

use App\Enum\Currency;
use App\Service\CurrencyService;

class CurrencyControllerTest extends ControllerTestCase
{
    public function testShouldRedirectToHomepageWhenCurrencyCodeIsInvalidInChartRoute()
    {
        $this->requestGet('/currency/chart/cdx');
        $this->assertResponseRedirects('/');
    }

    public function testShouldRedirectToChartWithTheNumberOfDaysSpecifiedAsLimitIfTheSelectedNumberIsTooHigh()
    {
        $this->requestGet('/currency/chart/eur/days/99');
        $this->assertResponseRedirects('/currency/chart/eur/days/' . CurrencyService::LIMIT_OF_NUMBER_OF_DAYS_ON_CHART);
    }

    public function testShouldReturnChartData()
    {
        $numberOfDays = 5;
        $this->requestPost('/api/currency/chart', [
            'currencyCode' => Currency::EURO->getCode(),
            'numberOfDays' => 5,
        ]);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount($numberOfDays, json_decode($response->getContent()));
    }
}