<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Exchange;
use App\Entity\UserAccount;
use App\Enum\Currency;
use App\Repository\UserAccountRepository;
use App\Service\CurrencyService;
use App\Service\UserAccountService;
use App\Tests\DatabaseDependantTestCase;

class UserAccountServiceTest extends DatabaseDependantTestCase
{
    private ?UserAccountService $userAccountService;
    private ?UserAccountRepository $userAccountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userAccountService = $this->client->getContainer()->get(UserAccountService::class);
        $this->userAccountRepository = $this->getRepository(UserAccount::class);
    }

    public function testShouldReturnTrueWhenAccountBalanceIsSufficient()
    {
        $currency = Currency::US_DOLLAR;
        $this->createUserAccount($this->getLoggedUser(), 150, $currency);
        $this->assertTrue($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    public function testShouldReturnFalseWhenAccountBalanceIsNotSufficient()
    {
        $currency = Currency::US_DOLLAR;
        $this->createUserAccount($this->getLoggedUser(), 50, $currency);
        $this->assertFalse($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    public function testShouldUpdateUserAccountsBalances()
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
}