<?php

namespace App\Tests\Service;

use App\Entity\Exchange;
use App\Enum\Currency;
use App\Repository\UserAccountRepository;
use App\Service\CurrencyService;
use App\Service\UserAccountService;
use App\Tests\DatabaseDependantTestCase;

class UserAccountServiceTest extends DatabaseDependantTestCase
{
    private ?UserAccountService $userAccountService;
    private ?UserAccountRepository $userAccountRepository;

    /** @test */
    public function should_return_true_when_account_balance_is_sufficient()
    {
        $currency = Currency::US_DOLLAR;
        $this->createUserAccount($this->getLoggedUser(), 150, $currency);
        $this->assertTrue($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    /** @test */
    public function should_return_false_when_account_balance_is_not_sufficient()
    {
        $currency = Currency::US_DOLLAR;
        $this->createUserAccount($this->getLoggedUser(), 50, $currency);
        $this->assertFalse($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    /** @test */
    public function should_update_user_accounts_balances()
    {
        $user = $this->getLoggedUser();
        $primaryCurrencyUserAccount = $this->createUserAccount($user, 100, Currency::EURO);
        $exchange = new Exchange();
        $exchange
            ->setPrimaryCurrency($primaryCurrencyUserAccount->getCurrency())
            ->setTargetCurrency(Currency::POLISH_ZLOTY)
            ->setAmount(80)
            ->setAmountAfterExchange(CurrencyService::getConversion($exchange));
        $this->userAccountService->updateAccountsBalances($exchange);
        $this->assertEquals(20, $primaryCurrencyUserAccount->getAmount());
        $targetCurrencyUserAccount = $this->userAccountRepository->findOneByUserAndCurrency($user, $exchange->getTargetCurrency());
        $this->assertEquals($exchange->getAmountAfterExchange(), $targetCurrencyUserAccount->getAmount());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userAccountService = $this->client->getContainer()->get(UserAccountService::class);
        $this->userAccountRepository = $this->client->getContainer()->get(UserAccountRepository::class);
    }
}