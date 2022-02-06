<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Entity\UserAccount;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

class ExchangeService
{
    private $user;

    public function __construct(private CurrencyService $currencyService, private UserAccountService $userAccountService, private Security $security, private EntityManagerInterface $entityManager)
    {
        $this->user = $this->security->getUser();
    }

    public function getCurrencyConversion(array $formData): float
    {
        $primaryCurrency = $formData['primaryCurrency'];
        $targetCurrency = $formData['targetCurrency'];
        $primaryCurrencyRate = $primaryCurrency === 'pln' ? 1 : $this->currencyService->getCurrentRate($primaryCurrency);
        $targetCurrencyRate = $targetCurrency === 'pln' ? 1 : $this->currencyService->getCurrentRate($targetCurrency);

        return round(($formData['amount'] * $primaryCurrencyRate) / $targetCurrencyRate, 2);
    }

    /**
     * @throws Exception
     */
    public function createExchange(array $formData): void
    {
        try {
            $amountAfterExchange = $this->getCurrencyConversion($formData);
            $exchange = new Exchange();
            $exchange
                ->setUser($this->user)
                ->setPrimaryCurrency($formData['primaryCurrency'])
                ->setTargetCurrency($formData['targetCurrency'])
                ->setAmount($formData['amount'])
                ->setAmountAfterExchange($amountAfterExchange)
                ->setDate(new \DateTime());
            $this->entityManager->persist($exchange);
            $this->entityManager->flush();

            $this->userAccountService->changeAccountsBalances($formData, $amountAfterExchange);
        } catch(Exception) {
            throw new Exception('The exchange was unsuccessful');
        }
    }

}
