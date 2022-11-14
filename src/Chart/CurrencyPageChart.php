<?php

declare(strict_types=1);

namespace App\Chart;

use App\Enum\Currency;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Model\Chart as ChartJs;

class CurrencyPageChart extends Chart
{
    public function build(): ChartJs
    {
        $chart = (new ChartBuilder())->createChart(ChartJs::TYPE_LINE);
        $chart->setData([
            'labels' => $this->getLabels(),
            'datasets' => [
                [
                    'label' => $this->getTitle(),
                    'borderColor' => 'rgb(34, 72, 196)',
                    'data' => $this->getData(),
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
}