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
    public function createExchange(array $formData): ?Exchange
    {
        if (!$this->userAccountService->isAccountBalanceSufficient($formData[Exchange::PRIMARY_CURRENCY], $formData[Exchange::AMOUNT])) {
            throw new ExchangeException('You have insufficient funds in that currency.');
        }
        $amountAfterExchange = CurrencyService::getConversion($formData);
        $exchange = new Exchange($this->getUser(), $formData[Exchange::AMOUNT], $amountAfterExchange, $formData[Exchange::PRIMARY_CURRENCY], $formData[Exchange::TARGET_CURRENCY]);
        $this->entityManager->persist($exchange);
        $this->entityManager->flush();
        $this->userAccountService->changeAccountsBalances($formData, $amountAfterExchange);

        return $exchange;
    }

    /**
     * @return array{primaryCurrency: string, targetCurrency: string, amount: float}
     */
    public function getDataFromForm(FormInterface $form): array
    {
        $formData = [];
        foreach ([Exchange::PRIMARY_CURRENCY, Exchange::TARGET_CURRENCY, Exchange::AMOUNT] as $field) {
            $formData[$field] = $form[$field]->getData();
        }

        return $formData;
    }
}
