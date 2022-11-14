<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Currency;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    private const X_AXIS_KEY = 'days';
    private const Y_AXIS_KEY = 'rates';

    public static function getChart(Currency $currency, int $numberOfDays): Chart
    {
        $axesData = self::getAxesData($currency, $numberOfDays);
        $chart = (new ChartBuilder())->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $axesData[self::X_AXIS_KEY],
            'datasets' => [
                [
                    'label' => self::getLabel($currency, $numberOfDays),
                    'borderColor' => 'rgb(34, 72, 196)',
                    'data' => $axesData[self::Y_AXIS_KEY],
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

    #[ArrayShape([
        'type' => "string",
        'data' => "array"
    ])]
    public static function getJsConfig(Currency $currency, int $numberOfDays): array
    {
        $axesData = self::getAxesData($currency, $numberOfDays);

        return [
            'type' => Chart::TYPE_LINE,
            'data' => [
                'labels' => $axesData[self::X_AXIS_KEY],
                'datasets' => [
                    [
                        'data' => $axesData[self::Y_AXIS_KEY],
                        'borderColor' => 'rgb(34, 72, 196)',
                        'fill' => false,
                        'label' => self::getLabel($currency, $numberOfDays),
                    ],
                ],
            ]
        ];
    }

    private static function getAxesData(Currency $currency, int $numberOfDays): array
    {
        $data = [];
        foreach(CurrencyService::getLastDaysRates($currency, $numberOfDays) as $rate) {
            $data[self::X_AXIS_KEY][] = $rate['date'];
            $data[self::Y_AXIS_KEY][] = round($rate['rate'], 3);
        }

        return $data;
    }

    private static function getLabel(Currency $currency, int $numberOfDays): string
    {
        return $currency->getName() . " fluctuations in last $numberOfDays days";
    }
}