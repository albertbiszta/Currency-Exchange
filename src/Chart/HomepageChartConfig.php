<?php

declare(strict_types=1);

namespace App\Chart;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\UX\Chartjs\Model\Chart as ChartJs;

class HomepageChartConfig extends Chart
{
    #[ArrayShape([
        'type' => "string",
        'data' => "array",
    ])]
    public function build(): array
    {
        return [
            'type' => ChartJs::TYPE_LINE,
            'data' => [
                'labels' => $this->getLabels(),
                'datasets' => [
                    [
                        'data' => $this->getData(),
                        'borderColor' => 'rgb(34, 72, 196)',
                        'fill' => false,
                        'label' => $this->getTitle(),
                    ],
                ],
            ],
        ];
    }
}