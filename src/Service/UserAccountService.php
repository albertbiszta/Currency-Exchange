<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Entity\UserAccount;
use App\Enum\Currency;
use App\Exception\WithdrawException;
use App\Repository\UserAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserAccountService extends Service
{
    public function __construct(private readonly UserAccountRepository $userAccountRepository, protected Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function isAccountBalanceSufficient(Currency $primaryCurrency, float $exchangeAmount): bool
    {
        $currencyAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $primaryCurrency);
        return $currencyAccount && ($currencyAccount->getAmount() >= $exchangeAmount);
    }

    public function updateAccountsBalances(Exchange $exchange): void
    {
        $primaryCurrencyUserAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $exchange->getPrimaryCurrency());
        $primaryCurrencyUserAccount->setAmount($primaryCurrencyUserAccount->getAmount() - $exchange->getAmount());
        $this->saveEntity($primaryCurrencyUserAccount);
        $this->addToAccount($exchange->getTargetCurrency(), $exchange->getAmountAfterExchange());
    }

    public function addToAccount(Currency $targetCurrency, float $amountAfterExchange): void
    {
        $userAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $targetCurrency);
        if ($userAccount) {
            $userAccount->setAmount($userAccount->getAmount() + $amountAfterExchange);
        } else {
            $userAccount = new UserAccount($this->getUser(), $amountAfterExchange, $targetCurrency);
        }
        $this->saveEntity($userAccount);
    }

    /**
     * @throws \App\Exception\WithdrawException
     */
    public function subtractFromAccount(Currency $currency, float $amount): void
    {
        $userAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $currency);
        if (!$userAccount) {
            throw new WithdrawException('You do not have an account in this currency yet. Deposit funds.');
        }
        $newAmount = $userAccount->getAmount() - $amount;
        if ($newAmount < 0) {
            throw new WithdrawException('You have insufficient funds. Your account balance is ' . $userAccount->getAmount() . ' ' . $userAccount->getCurrency()->getName());
        }
        $userAccount->setAmount($newAmount);
    }
}
