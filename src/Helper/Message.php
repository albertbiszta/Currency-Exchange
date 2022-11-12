<?php

namespace App\Helper;

use App\Enum\Currency;

class Message
{
    public static function getCurrencyPercentageChangeMessage(float $change): string
    {
        return "Percentage change in the value of a currency between the first and last days of the chart: $change %";
    }

    public static function getChartLabel(Currency $currency, int $numberOfDays): string
    {
        return  $currency->getName() . " fluctuations in last $numberOfDays days";
    }
}