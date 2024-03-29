<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Exchange;
use App\Exception\InsufficientFoundsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ExchangeService extends Service
{
    public function __construct(protected EntityManagerInterface $entityManager, protected Security $security, private readonly UserAccountService $userAccountService)
    {
        parent::__construct($entityManager, $security);
    }

    /**
     * @throws \App\Exception\InsufficientFoundsException
     */
    public function createExchange(Exchange $exchange): ?Exchange
    {
        if (!$this->userAccountService->isAccountBalanceSufficient($exchange->getPrimaryCurrency(), $exchange->getAmount())) {
            throw new InsufficientFoundsException();
        }
        $amountAfterExchange = CurrencyService::getConversion($exchange);
        $exchange->setMissingAttributes($this->getUser(), $amountAfterExchange);
        $this->saveEntity($exchange);
        $this->userAccountService->updateAccountsBalances($exchange);

        return $exchange;
    }
}
