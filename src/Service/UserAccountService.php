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
        $currencyAccount = $this->userAccountRepository->getUserAccountByCurrency($primaryCurrency, $this->user);
        return $currencyAccount && ($currencyAccount->getAmount() >= $exchangeAmount);
    }

    public function changeAccountsBalances(array $formData, float $amountAfterExchange): void
    {
        $primaryCurrencyUserAccount = $this->userAccountRepository->getUserAccountByCurrency($formData[Exchange::PRIMARY_CURRENCY], $this->user);
        $primaryCurrencyUserAccount->setAmount($primaryCurrencyUserAccount->getAmount() - $formData[Exchange::AMOUNT]);
        $this->entityManager->persist($primaryCurrencyUserAccount);
        $this->entityManager->flush();
        $this->addToAccount($formData[Exchange::TARGET_CURRENCY], $amountAfterExchange);
    }

    public function addToAccount(string $targetCurrency, float $amountAfterExchange): void
    {
        $targetCurrencyUserAccount = $this->userAccountRepository->getUserAccountByCurrency($targetCurrency, $this->user);
        if (!$targetCurrencyUserAccount) {
            (new UserAccount())
                ->setUser($this->user)
                ->setAmount($amountAfterExchange)
                ->setCurrency($targetCurrency);
        } else {
            $targetCurrencyUserAccount->setAmount($targetCurrencyUserAccount->getAmount() + $amountAfterExchange);
        }
        $this->entityManager->persist($targetCurrencyUserAccount);
        $this->entityManager->flush();
    }
}
