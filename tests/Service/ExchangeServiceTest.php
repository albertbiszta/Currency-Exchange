<?php

namespace App\Tests\Service;

use App\Entity\Exchange;
use App\Exception\ExchangeException;
use App\Repository\UserAccountRepository;
use App\Service\CurrencyService;
use App\Service\ExchangeService;
use App\Tests\DatabaseDependantTestCase;
use JetBrains\PhpStorm\ArrayShape;

class ExchangeServiceTest extends DatabaseDependantTestCase
{
    private ?ExchangeService $exchangeService;
    private ?UserAccountRepository $userAccountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeService = $this->client->getContainer()->get(ExchangeService::class);
        $this->userAccountRepository = $this->client->getContainer()->get(UserAccountRepository::class);
    }

    /** @test */
    public function should_throw_an_exception_when_account_balance_is_insufficient()
    {
        $this->expectException(ExchangeException::class);
        $user = $this->getLoggedUser();
        $this->createUserAccount($user, 50, CurrencyService::POLISH_ZLOTY_SHORTNAME);
        $this->exchangeService->createExchange($this->getFormData());
    }

    /** @test */
    public function should_create_an_exchange_and_update_account_balance()
    {
        $user = $this->getLoggedUser();
        $this->assertEquals(0, $this->getNumberOfUserAccounts());
        $userAccount = $this->createUserAccount($user, 1000, CurrencyService::POLISH_ZLOTY_SHORTNAME);
        $this->assertEquals(1, $this->getNumberOfUserAccounts());
        $this->exchangeService->createExchange($this->getFormData());
        $this->assertLessThan( 1000, $userAccount->getAmount());
        $this->assertEquals(2, $this->getNumberOfUserAccounts());
    }

    #[ArrayShape([
        Exchange::PRIMARY_CURRENCY => "string",
        Exchange::TARGET_CURRENCY => "string",
        Exchange::AMOUNT => "int"
    ])]
    private function getFormData(): array
    {
        return [
            Exchange::PRIMARY_CURRENCY => CurrencyService::POLISH_ZLOTY_SHORTNAME,
            Exchange::TARGET_CURRENCY => CurrencyService::EURO_SHORTNAME,
            Exchange::AMOUNT => 100,
        ];
    }

    private function getNumberOfUserAccounts(): int
    {
        return count($this->userAccountRepository->findAll());
    }
}
