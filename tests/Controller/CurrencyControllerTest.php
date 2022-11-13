<?php

declare(strict_types=1);

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

    public function testShouldReturnCorrectChartData()
    {
        $numberOfDays = 5;
        $this->requestPost('/api/currency/chart', [
            'currencyCode' => Currency::EURO->getCode(),
            'numberOfDays' => $numberOfDays,
        ]);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $chartConfig = (array) json_decode($response->getContent());
        $this->assertCount($numberOfDays, $chartConfig['data']->labels);
        $this->assertCount($numberOfDays, $chartConfig['data']->datasets[0]->data);
    }
}