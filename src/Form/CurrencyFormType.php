<?php

namespace App\Form;

use App\Entity\Exchange;
use App\Enum\Currency;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
