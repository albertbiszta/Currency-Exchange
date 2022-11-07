<?php

namespace App\Form;

use App\Entity\Exchange;
use App\Enum\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExchangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(Exchange::ATTRIBUTE_PRIMARY_CURRENCY, ChoiceType::class, [
                'choices' => Currency::getFormChoices(),
            ])
            ->add(Exchange::ATTRIBUTE_TARGET_CURRENCY, ChoiceType::class, [
                'choices' => Currency::getFormChoices(),
            ])
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
