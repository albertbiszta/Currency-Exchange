<?php

namespace App\Exception;

class ExchangeException extends \Exception
{
    protected $message = 'You have insufficient funds in that currency.';
}