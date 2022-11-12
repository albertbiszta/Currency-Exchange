<?php

namespace App\Tests\Service;

use App\Entity\Exchange;
use App\Entity\UserAccount;
use App\Enum\Currency;
use App\Exception\ExchangeException;
use App\Repository\UserAccountRepository;
use App\Service\ExchangeService;
use App\Tests\DatabaseDependantTestCase;

class ExchangeServiceTest extends DatabaseDependantTestCase
{
    private ?ExchangeService $exchangeService;
    private ?UserAccountRepository $userAccountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeService = $this->client->getContainer()->get(ExchangeService::class);
        $this->userAccountRepository = $this->getRepository(UserAccount::class);
    }

    public function testShouldThrowAnExceptionWhenAccountBalanceIsInsufficient()
    {
        $this->expectException(ExchangeException::class);
        $user = $this->getLoggedUser();
        $userAccount = $this->createUserAccount($user, 50, Currency::POLISH_ZLOTY);
        $this->createExchange($userAccount->getCurrency(),);
    }

    public function testShouldCreateAnExchangeAndUpdateAccountBalance()
    {
        $user = $this->getLoggedUser();
        $this->assertEquals(0, $this->getNumberOfUserAccounts());
        $userAccount = $this->createUserAccount($user, 1000, Currency::POLISH_ZLOTY);
        $this->assertEquals(1, $this->getNumberOfUserAccounts());
        $this->createExchange($userAccount->getCurrency());
        $this->assertLessThan( 1000, $userAccount->getAmount());
        $this->assertEquals(2, $this->getNumberOfUserAccounts());
    }

    private function createExchange(Currency $primaryCurrency): void
    {
        $exchange = new Exchange();
        $exchange
            ->setPrimaryCurrency($primaryCurrency)
            ->setTargetCurrency(Currency::EURO)
            ->setAmount(100);
        $this->exchangeService->createExchange($exchange);
    }

    private function getNumberOfUserAccounts(): int
    {
        return count($this->userAccountRepository->findAll());
    }
}
