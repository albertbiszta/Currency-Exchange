<?php

declare(strict_types=1);

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
        protected EntityManagerInterface $entityManager,
        protected Security               $security,
        private                          readonly UrlGeneratorInterface $router,
        private                          readonly UserAccountService $userAccountService,
        private                          readonly PaymentRepository $paymentRepository
    ) {
        parent::__construct($this->entityManager, $security);
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
                        'currency' => $payment->getCurrency()->getCode(),
                        'product_data' => ['name' => 'Currency payment'],
                        'unit_amount' => $payment->getAmount() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->getRoutePath('deposit_complete', ['paymentId' => $payment->getId()]),
            'cancel_url' => $this->getRoutePath('deposit_cancel_url'),
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


    private function getRoutePath(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
