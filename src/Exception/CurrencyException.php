<?php

namespace App\Exception;

class CurrencyException extends \Exception
{
    protected $message = 'Incorrect currency slug';
}