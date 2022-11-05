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

    public function changeAccountsBalances(array $formData, float $amountAfterExchange): void
    {
        $primaryCurrencyUserAccount = $this->userAccountRepository->findOneByUserAndCurrency($this->getUser(), $formData[Exchange::PRIMARY_CURRENCY]);
        $primaryCurrencyUserAccount->setAmount($primaryCurrencyUserAccount->getAmount() - $formData[Exchange::AMOUNT]);
        $this->entityManager->persist($primaryCurrencyUserAccount);
        $this->entityManager->flush();
        $this->addToAccount($formData[Exchange::TARGET_CURRENCY], $amountAfterExchange);
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
        $this->save(new UserAccount($this->getUser(), $amount, $currency));
    }

    private function updateAmount(UserAccount $userAccount, float $amountAfterExchange): void
    {
        $userAccount->setAmount($userAccount->getAmount() + $amountAfterExchange);
        $this->save($userAccount);
    }

    private function save(UserAccount $userAccount): void
    {
        $this->entityManager->persist($userAccount);
        $this->entityManager->flush();
    }
}
