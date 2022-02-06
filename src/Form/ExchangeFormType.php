<?php

namespace App\Form;

use App\Entity\Exchange;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExchangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('primaryCurrency', ChoiceType::class, [
            'choices' => [
                'Polish Zloty' => 'pln',
                'U.S. Dollar' => 'usd',
                'Euro' => 'eur',
                'Swiss Franc' => 'chf',
                'Pound Sterling' => 'gbp',

            ],
        ])
            ->add('targetCurrency', ChoiceType::class, [
                'choices' => [
                    'U.S. Dollar' => 'usd',
                    'Polish Zloty' => 'pln',
                    'Euro' => 'eur',
                    'Swiss Franc' => 'chf',
                    'Pound Sterling' => 'gbp',

                ],
            ])
            ->add('amount', NumberType::class)
            ->add('submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exchange::class,
        ]);
    }
}
