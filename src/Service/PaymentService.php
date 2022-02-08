<?php

namespace App\Service;

use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class PaymentService extends Service
{
    public function __construct(private UrlGeneratorInterface $router, Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function paymentHandle(Payment $payment): Session
    {
        $payment
            ->setUser($this->user)
            ->setDate(new \DateTime())
            ->setIsCompleted(0);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        Stripe::setApiKey($_ENV['APP_STRIPE_SK']);

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $payment->getCurrency(),
                        'product_data' => ['name' => 'Currency payment'],
                        'unit_amount' => $payment->getAmount() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->router->generate('payment_status_change', ['paymentId' => $payment->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->router->generate('payment_cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    public function setPaymentAsCompleted(Payment $payment): void
    {
        $payment->setIsCompleted(1);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }
}
