<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ExchangeService
{
    private $user;

    public function __construct(private CurrencyService $currencyService, private Security $security, private EntityManagerInterface $entityManager)
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

    public function createExchange(array $formData): void
    {
        $exchange = new Exchange();
        $exchange
            ->setUser($this->user)
            ->setPrimaryCurrency($formData['primaryCurrency'])
            ->setTargetCurrency($formData['targetCurrency'])
            ->setAmount($formData['amount'])
            ->setAmountAfterExchange($this->getCurrencyConversion($formData))
            ->setDate(new \DateTime());

        $this->entityManager->persist($exchange);
        $this->entityManager->flush();

        //mailer
    }

}
