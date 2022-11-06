<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\Exchange;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;

class CurrencyService
{
    private const URL = 'https://api.nbp.pl/api/exchangerates/rates/a/';

    public static function getLastDaysRatesForCurrency(string $currency, int $numberOfDays): array
    {
        return array_map(fn($dayData) => [
            'date' => $dayData['effectiveDate'],
            'rate' => $dayData['mid'],
        ], self::getApiResponse($currency, "/last/$numberOfDays")['rates']);
    }

    public static function getPercentageChangeForCurrency(string $currency): float
    {
        $rates = self::getLastDaysRatesForCurrency($currency, 2);
        $change = ($rates[1]['mid'] - $rates[0]['mid']) / $rates[0]['mid'];

        return round($change * 100, 2);
    }

    public static function getCurrentRate(string $currency): float
    {
        return ($currency === Currency::POLISH_ZLOTY_CODE) ?  1 : self::getApiResponse($currency)['rates'][0]['mid'];
    }

    public static function getConversion(Exchange $exchange): float
    {
        $primaryCurrencyRate = self::getCurrentRate($exchange->getPrimaryCurrency());
        $targetCurrencyRate = self::getCurrentRate($exchange->getTargetCurrency());

        return round(($exchange->getAmount() * $primaryCurrencyRate) / $targetCurrencyRate, 2);
    }

    public static function getDataForRatesChangeChart(string $currency, int $numberOfDays): array
    {
        return array_map(fn($dayData) => [
            'date' => $dayData['effectiveDate'],
            'rate' => $dayData['mid'],
        ], self::getLastDaysRatesForCurrency($currency, $numberOfDays));
    }

    public static function getChart(string $currency, int $numberOfDays): Chart
    {
        $days = [];
        $rates = [];
        foreach(self::getLastDaysRatesForCurrency($currency, $numberOfDays) as $rate) {
            $days[] = $rate['date'];
            $rates[] = round($rate['rate'], 3);
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

    private static function getApiResponse(string $currency, string $extraPath = ''): array
    {
        return HttpClient::create()->request('GET', self::URL . $currency . $extraPath)->toArray();
    }
}
