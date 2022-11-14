<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Service\Service;
use App\Service\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

final class DepositService extends Service
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Security               $security,
        private                          readonly UrlGeneratorInterface $urlGenerator,
        private                          readonly UserAccountService $userAccountService,
        private                          readonly PaymentRepository $paymentRepository
    ) {
        parent::__construct($this->entityManager, $security);
    }

    public function handle(Payment $payment): Session
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
            'success_url' => $this->generateUrl('deposit_complete', ['paymentId' => $payment->getId()]),
            'cancel_url' => $this->generateUrl('deposit_cancel'),
        ]);
    }

    public function complete(int $paymentId): void
    {
        $payment = $this->paymentRepository->findOneBy(['id' => $paymentId]);
        $payment->setIsCompleted(true);
        $this->saveEntity($payment);
        $this->userAccountService->addToAccount($payment->getCurrency(), $payment->getAmount());
    }

    private function generateUrl(string $name, array $params = []): string
    {
        return $this->urlGenerator->generate($name, $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
