<?php

declare(strict_types=1);

namespace App\Chart;

use App\Enum\Currency;
use App\Service\CurrencyService;

abstract class Chart
{
    private array $days = [];
    private array $rates = [];

    public function __construct(private readonly Currency $currency, private readonly int $numberOfDays)
    {
        $this->setAxesData();
    }

    protected function getLabels(): array
    {
        return $this->days;
    }

    protected function getData(): array
    {
        return $this->rates;
    }

    protected function getTitle(): string
    {
        return $this->currency->getName() . " fluctuations in last $this->numberOfDays days";
    }

    private function setAxesData(): void
    {
        foreach(CurrencyService::getLastDaysRates($this->currency, $this->numberOfDays) as $rate) {
            $this->days[] = $rate['date'];
            $this->rates[] = round($rate['rate'], 3);
        }
    }

}