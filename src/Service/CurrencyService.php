<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;


class CurrencyService
{
    private const url = 'http://api.nbp.pl/api/exchangerates/rates/a/';

    public function getLastDaysRatesForCurrency(string $currency, int $numberOfDays): array
    {
        return array_map(fn($dayData) => ['date' => $dayData['effectiveDate'], 'rate' => $dayData['mid']], $this->getApiResponse($currency, "/last/$numberOfDays")['rates']);
    }

    public function getPercentageChangeForCurrency(string $currency): float
    {
        $rates = $this->getLastDaysRatesForCurrency($currency, 2);
        $change = ($rates[1]['mid'] - $rates[0]['mid']) / $rates[0]['mid'];

        return round($change * 100, 2);
    }

    public function getCurrentRate(string $currency): float
    {
        return $this->getApiResponse($currency)['rates'][0]['mid'];
    }

    public function getDataForRatesChangeChart(string $currency, int $numberOfDays): array
    {
        return array_map(fn($dayData) => ['date' => $dayData['effectiveDate'], 'rate' => $dayData['mid']], $this->getLastDaysRatesForCurrency($currency, $numberOfDays));
    }

    private function getApiResponse(string $currency, string $extraPath = ''): array
    {
        return HttpClient::create()->request('GET', self::url . $currency . $extraPath)->toArray();
    }

}
