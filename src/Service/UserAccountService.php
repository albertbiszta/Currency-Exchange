<?php

namespace App\Service;

use App\Entity\UserAccount;
use App\Repository\UserAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserAccountService extends Service
{
    public function __construct(private UserAccountRepository $userAccountRepository, Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function isAccountBalanceSufficient(string $primaryCurrency, float $exchangeAmount): bool
    {
        return $this->userAccountRepository->getUserAccountByCurrency($primaryCurrency, $this->user)->getAmount() >= $exchangeAmount;
    }

    public function changeAccountsBalances(array $formData, float $amountAfterExchange): void
    {
        $primaryCurrencyUserAccount = $this->userAccountRepository->getUserAccountByCurrency($formData['primaryCurrency'], $this->user);
        $primaryCurrencyUserAccount->setAmount($primaryCurrencyUserAccount->getAmount() - $formData['amount']);
        $this->entityManager->persist($primaryCurrencyUserAccount);
        $this->entityManager->flush();
        $this->addToAccount($formData['targetCurrency'], $amountAfterExchange);
    }

    public function addToAccount(string $targetCurrency, float $amountAfterExchange): void
    {
        $targetCurrencyUserAccount = $this->userAccountRepository->getUserAccountByCurrency($targetCurrency, $this->user);
        if (!$targetCurrencyUserAccount) {
            $targetCurrencyUserAccount = new UserAccount();
            $targetCurrencyUserAccount
                ->setUser($this->user)
                ->setAmount($amountAfterExchange)
                ->setCurrency($targetCurrency);
        } else {
            $targetCurrencyUserAccount->setAmount($targetCurrencyUserAccount->getAmount() + $amountAfterExchange);
        }
        $this->entityManager->persist($targetCurrencyUserAccount);
        $this->entityManager->flush();
    }

    public function userAccountExists(string $currency): bool
    {
        return $this->userAccountRepository->count(['User' => $this->user, 'currency' => $currency]);
    }
}
