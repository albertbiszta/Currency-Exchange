<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Exception\ExchangeException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class ExchangeService extends Service
{
    public function __construct(private UserAccountService $userAccountService, protected Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    /**
     * @throws \Exception
     */
    public function createExchange(Exchange $exchange): ?Exchange
    {
        if (!$this->userAccountService->isAccountBalanceSufficient($exchange->getPrimaryCurrency(),$exchange->getAmount())) {
            throw new ExchangeException('You have insufficient funds in that currency.');
        }

        $amountAfterExchange = CurrencyService::getConversion($exchange);
        $exchange->setMissingAttributes($this->getUser(), $amountAfterExchange);
        $this->saveEntity($exchange);
        $this->userAccountService->changeAccountsBalances($exchange);

        return $exchange;
    }
}
