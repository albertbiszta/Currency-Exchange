<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Form\ExchangeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class ExchangeService extends Service
{
    public function __construct(private CurrencyService $currencyService, private UserAccountService $userAccountService, Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function getCurrencyConversion(array $formData): float
    {
        $primaryCurrency = $formData['primaryCurrency'];
        $targetCurrency = $formData['targetCurrency'];
        $primaryCurrencyRate = $primaryCurrency === 'pln' ? 1 : $this->currencyService->getCurrentRate($primaryCurrency);
        $targetCurrencyRate = $targetCurrency === 'pln' ? 1 : $this->currencyService->getCurrentRate($targetCurrency);

        return round(($formData['amount'] * $primaryCurrencyRate) / $targetCurrencyRate, 2);
    }

    public function createExchange(array $formData): void
    {
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
    }

    public function getDataFromForm(FormInterface $form): array
    {
        $formData = [];
        foreach (['primaryCurrency', 'targetCurrency', 'amount'] as $field) {
            $formData[$field] = $form[$field]->getData();
        }
        return $formData;
    }
}
