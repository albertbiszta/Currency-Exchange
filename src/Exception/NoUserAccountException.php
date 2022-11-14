<?php

declare(strict_types=1);

namespace App\Exception;

final class NoUserAccountException extends \Exception
{
    protected $message= 'You do not have an account in this currency yet. Deposit funds.';
}