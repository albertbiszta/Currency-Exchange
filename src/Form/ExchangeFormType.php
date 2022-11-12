<?php

namespace App\Form;

use App\Entity\Exchange;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExchangeFormType extends CurrencyFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(Exchange::ATTRIBUTE_PRIMARY_CURRENCY, EnumType::class, $this->buildCurrencySelectOptions())
            ->add(Exchange::ATTRIBUTE_TARGET_CURRENCY, EnumType::class, $this->buildCurrencySelectOptions())
            ->add(Exchange::ATTRIBUTE_AMOUNT, NumberType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exchange::class,
        ]);
    }
}
