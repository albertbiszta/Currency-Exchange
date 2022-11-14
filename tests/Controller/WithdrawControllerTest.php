<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Enum\Currency;
use App\Exception\InsufficientFoundsException;
use App\Exception\NoUserAccountException;

final class WithdrawControllerTest extends ControllerTestCase
{
    public function testShouldShowErrorMessageWhenUserDoesNotHaveAccountInCurrencyDuringWithdraw()
    {
        $this->getLoggedUser();
        $this->sendForm();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextNotContains('div', (new NoUserAccountException())->getMessage());
    }

    public function testShouldShowErrorMessageWhenUserDoesNotHaveEnoughFoundsToWithdraw()
    {
        $userAccount = $this->createUserAccount($this->getLoggedUser(), 500, Currency::EURO);
        $this->sendForm();
        $exceptionMessage = (new InsufficientFoundsException(InsufficientFoundsException::buildMessageWithAccountBalance($userAccount)))->getMessage();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextNotContains('div', $exceptionMessage);
    }

    protected function sendForm(): void
    {
        $requestParams = [
            'payment_form' => [
                'currency' => Currency::EURO->getCode(),
                'amount' => 1000,
            ],
        ];
        parent::requestPost('/withdraw', $requestParams);
    }
}