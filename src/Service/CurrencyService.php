<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;


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

    public function getChart(string $currency, int $numberOfDays): Chart
    {
        $days = [];
        $rates = [];

        foreach($this->getLastDaysRatesForCurrency($currency, $numberOfDays) as $rate) {
            array_push($days, $rate['date']);
            array_push($rates, $rate['rate']);
        }

        $chart = (new ChartBuilder())->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $days,
            'datasets' => [
                [
                    'label' => strtoupper($currency) . ' fluctuations in recent days',
                    'borderColor' => 'rgb(34, 72, 196)',
                    'data' => $rates,
                    'tension' => 0.0,
                ],
            ],
        ]);

        $chart->setOptions([
            'legend' => [
                'display' => 'false',
                'labels' => [
                    'fontColor' => 'black',
                ],
            ],
        ]);

        return $chart;
    }

    private function getApiResponse(string $currency, string $extraPath = ''): array
    {
        return HttpClient::create()->request('GET', self::url . $currency . $extraPath)->toArray();
    }

}
