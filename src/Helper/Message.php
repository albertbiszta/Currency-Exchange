<?php

namespace App\Helper;

class Message
{
    public static function getCurrencyPercentageChangeMessage(float $change): string
    {
        return "Percentage change in the value of a currency between the first and last days of the chart: $change %";
    }
}