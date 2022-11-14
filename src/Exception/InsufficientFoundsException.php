<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\UserAccount;

final class InsufficientFoundsException extends \Exception
{
    protected $message= 'You do not have an account in this currency yet. Deposit funds.';

    public static function buildMessageWithAccountBalance(UserAccount $userAccount): string
    {
        return 'You have insufficient funds. Your account balance is ' . $userAccount->getAmount() . ' ' . $userAccount->getCurrency()->getName();
    }
}