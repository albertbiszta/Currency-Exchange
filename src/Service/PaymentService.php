<?php

namespace App\Service;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class PaymentService extends Service
{
    public function __construct(
        protected Security               $security,
        protected EntityManagerInterface $entityManager,
        private UrlGeneratorInterface    $router,
        private UserAccountService       $userAccountService,
        private PaymentRepository        $paymentRepository
    ) {
        parent::__construct($security, $this->entityManager);
    }

    public function handleDeposit(Payment $payment): Session
    {
        $payment->setMissingAttributes($this->getUser());
        $this->saveEntity($payment);
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
            'success_url' => $this->router->generate('deposit_complete', ['paymentId' => $payment->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->router->generate('deposit_cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @throws \App\Exception\WithdrawException
     */
    public function handleWithdraw(Payment $payment)
    {
        $this->userAccountService->subtractFromAccount($payment->getCurrency(), $payment->getAmount());
        $payment->setMissingAttributes($this->getUser(), true);
        $this->saveEntity($payment);
    }

    public function completeDeposit(int $paymentId): void
    {
        $payment = $this->paymentRepository->findOneBy(['id' => $paymentId]);
        $payment->setIsCompleted(true);
        $this->saveEntity($payment);
        $this->userAccountService->addToAccount($payment->getCurrency(), $payment->getAmount());
    }
}
