<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Exception\ExchangeException;
use App\Form\ExchangeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class ExchangeService extends Service
{
    public function __construct(private UserAccountService $userAccountService, protected Security $security, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($security, $this->entityManager);
    }

    public function getCurrencyConversion(array $formData): float
    {
        $primaryCurrency = $formData[Exchange::PRIMARY_CURRENCY];
        $targetCurrency = $formData[Exchange::TARGET_CURRENCY];
        $primaryCurrencyRate = CurrencyService::getCurrentRate($primaryCurrency);
        $targetCurrencyRate = CurrencyService::getCurrentRate($targetCurrency);

        return round(($formData[Exchange::AMOUNT] * $primaryCurrencyRate) / $targetCurrencyRate, 2);
    }

    /**
     * @throws \Exception
     */
    public function createExchange(FormInterface $form): ?Exchange
    {
        $formData = $this->getDataFromForm($form);
        if (!$this->userAccountService->isAccountBalanceSufficient($formData[Exchange::PRIMARY_CURRENCY], $formData[Exchange::AMOUNT])) {
            throw new ExchangeException('You have insufficient funds in that currency.');
        }
        $amountAfterExchange = $this->getCurrencyConversion($formData);
        $exchange = new Exchange();
        $exchange
            ->setUser($this->user)
            ->setPrimaryCurrency($formData[Exchange::PRIMARY_CURRENCY])
            ->setTargetCurrency($formData[Exchange::TARGET_CURRENCY])
            ->setAmount($formData[Exchange::AMOUNT])
            ->setAmountAfterExchange($amountAfterExchange)
            ->setDate(new \DateTime());
        $this->entityManager->persist($exchange);
        $this->entityManager->flush();
        $this->userAccountService->changeAccountsBalances($formData, $amountAfterExchange);
        return $exchange;
    }

    /**
     * @return array{primaryCurrency: string, targetCurrency: string, amount: float}
     */
    private function getDataFromForm(FormInterface $form): array
    {
        $formData = [];
        foreach ([Exchange::PRIMARY_CURRENCY, Exchange::TARGET_CURRENCY, Exchange::AMOUNT] as $field) {
            $formData[$field] = $form[$field]->getData();
        }
        return $formData;
    }
}
