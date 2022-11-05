<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Entity\UserAccount;
use App\Repository\UserAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserAccountService extends Service
{
    public function __construct(private UserAccountRepository $userAccountRepository, protected Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function isAccountBalanceSufficient(string $primaryCurrency, float $exchangeAmount): bool
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

    public function addToAccount(string $targetCurrency, float $amountAfterExchange): void
    {
        $targetCurrencyUserAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $targetCurrency);
        if ($targetCurrencyUserAccount) {
            $this->updateAmount($targetCurrencyUserAccount, $amountAfterExchange);
        } else {
            $this->create($targetCurrency, $amountAfterExchange);
        }
    }

    private function create(string $currency, float $amount): void
    {
        $this->saveEntity(new UserAccount($this->getUser(), $amount, $currency));
    }

    private function updateAmount(UserAccount $userAccount, float $amountAfterExchange): void
    {
        $userAccount->setAmount($userAccount->getAmount() + $amountAfterExchange);
        $this->saveEntity($userAccount);
    }
}
