<?php

namespace App\Exception;

use App\Entity\UserAccount;

class WithdrawException extends \Exception
{
    public const NO_ACCOUNT_MESSAGE = 'You do not have an account in this currency yet. Deposit funds.';

    public static function getInsufficientFoundsMessage(UserAccount $userAccount): string
    {
        return 'You have insufficient funds. Your account balance is ' . $userAccount->getAmount() . ' ' . $userAccount->getCurrency()->getName();
    }
}