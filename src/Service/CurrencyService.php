<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Exchange;
use App\Enum\Currency;
use Symfony\Component\HttpClient\HttpClient;

class CurrencyService
{
    public const DEFAULT_NUMBER_OF_DAYS = 7;
    public const LIMIT_OF_NUMBER_OF_DAYS_ON_CHART = 90;
    private const URL = 'https://api.nbp.pl/api/exchangerates/rates/a/';

    public static function getConversion(Exchange $exchange): float
    {
        $primaryCurrencyRate = self::getCurrentRate($exchange->getPrimaryCurrency());
        $targetCurrencyRate = self::getCurrentRate($exchange->getTargetCurrency());

        return round(($exchange->getAmount() * $primaryCurrencyRate) / $targetCurrencyRate, 2);
    }

    public static function getLastDaysRates(Currency $currency, int $numberOfDays): array
    {
        return array_map(fn($dayData) => [
            'date' => $dayData['effectiveDate'],
            'rate' => $dayData['mid'],
        ], self::getApiResponse($currency, "/last/$numberOfDays")['rates']);
    }

    public static function getPercentageChangeMessage(Currency $currency, int $numberOfDays): string
    {
        $percentageChange = self::getPercentageChange($currency, $numberOfDays);

        return "Percentage change in the value of a currency between the first and last days of the chart: $percentageChange %";
    }

    private static function getCurrentRate(Currency $currency): float
    {
        return $currency->isMainCurrency() ?  1 : self::getApiResponse($currency)['rates'][0]['mid'];
    }

    private static function getPercentageChange(Currency $currency, int $numberOfDays): float
    {
        $rates = self::getLastDaysRates($currency, $numberOfDays);
        $lastIndex = count($rates) - 1;
        $change = (($rates[$lastIndex]['rate']) - $rates[0]['rate']) / $rates[0]['rate'];

        return round($change * 100, 2);
    }

    private static function getApiResponse(Currency $currency, string $extraPath = ''): array
    {
        return HttpClient::create()->request('GET', self::URL . $currency->getCode() . $extraPath)->toArray();
    }
}
