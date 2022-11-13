<?php

namespace App\Form;

use App\Enum\Currency;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Form\AbstractType;

abstract class CurrencyFormType extends AbstractType
{
    #[ArrayShape(['class' => "string", 'choice_label' => "\Closure"])]
    protected function buildCurrencySelectOptions(): array
    {
        return [
            'class' => Currency::class,
            'choice_label' => fn(Currency $currency) => $currency->getName(),
        ];
    }
}
