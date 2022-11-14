<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Form\PaymentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class PaymentController extends AbstractController
{
    protected const VIEW_TITLE = 'Make deposit';

    protected function createPayment(Request $request, PaymentType $paymentType): Response
    {
        $form = $this->createForm(PaymentFormType::class, new Payment($paymentType));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           return static::handleFormSubmit($form->getData());
        }

        return $this->renderPaymentView($form);
    }

    protected function renderPaymentView(FormInterface $form): Response
    {
        return $this->render('payment/payment.html.twig', [
            'payment_form' => $form->createView(),
            'title' => static::VIEW_TITLE,
        ]);
    }

    protected abstract function handleFormSubmit(Payment $payment);
}
