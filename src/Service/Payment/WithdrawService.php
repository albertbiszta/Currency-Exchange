<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Service\Service;
use App\Service\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class WithdrawService extends Service
{
    public function __construct(protected EntityManagerInterface $entityManager, protected Security $security, private readonly UserAccountService $userAccountService,)
    {
        parent::__construct($this->entityManager, $security);
    }

    /**
     * @throws \App\Exception\WithdrawException
     */
    public function handle(Payment $payment): void
    {
        $this->userAccountService->subtractFromAccount($payment->getCurrency(), $payment->getAmount());
        $payment->setMissingAttributes($this->getUser(), true);
        $this->saveEntity($payment);
    }
}
