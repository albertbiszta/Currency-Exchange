<?php

namespace App\Service;

use App\Entity\Exchange;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;

class CurrencyService
{
    public const EURO_SHORTNAME = 'eur';
    public const POLISH_ZLOTY_SHORTNAME = 'pln';
    public const POUND_STERLING_SHORTNAME = 'gbp';
    public const SWISS_FRANC_SHORTNAME = 'chf';
    public const US_DOLLAR_SHORTNAME = 'usd';

    public const CURRENCY_CHOICES = [
        'Euro' => self::EURO_SHORTNAME,
        'Polish Zloty' => self::POLISH_ZLOTY_SHORTNAME,
        'Pound Sterling' => self::POUND_STERLING_SHORTNAME,
        'Swiss Franc' => self::SWISS_FRANC_SHORTNAME,
        'U.S. Dollar' => self::US_DOLLAR_SHORTNAME,
    ];

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
        return ($currency === self::POLISH_ZLOTY_SHORTNAME) ?  1 : self::getApiResponse($currency)['rates'][0]['mid'];
    }

    public static function getConversion(array $formData): float
    {
        $primaryCurrency = $formData[Exchange::PRIMARY_CURRENCY];
        $targetCurrency = $formData[Exchange::TARGET_CURRENCY];
        $primaryCurrencyRate = self::getCurrentRate($primaryCurrency);
        $targetCurrencyRate = self::getCurrentRate($targetCurrency);

        return round(($formData[Exchange::AMOUNT] * $primaryCurrencyRate) / $targetCurrencyRate, 2);
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
