<?php

namespace App\Tests\Service;

use App\Service\CurrencyService;
use App\Service\UserAccountService;
use App\Tests\DatabaseDependantTestCase;

class UserAccountServiceTest extends DatabaseDependantTestCase
{
    private ?UserAccountService $userAccountService;

    /** @test */
    public function should_return_true_when_account_balance_is_sufficient()
    {
        $currency = CurrencyService::US_DOLLAR_SHORTNAME;
        $this->createUserAccount($this->getLoggedUser(), 150, $currency);
        $this->assertTrue($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    /** @test */
    public function should_return_false_when_account_balance_is_not_sufficient()
    {
        $currency = CurrencyService::US_DOLLAR_SHORTNAME;
        $this->createUserAccount($this->getLoggedUser(), 50, $currency);
        $this->assertFalse($this->userAccountService->isAccountBalanceSufficient($currency, 100));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userAccountService = $this->client->getContainer()->get(UserAccountService::class);
    }
}