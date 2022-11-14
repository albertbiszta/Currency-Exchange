<?php

declare(strict_types=1);

namespace App\Exception;

final class CurrencyException extends \Exception
{
    protected $message = 'Incorrect currency slug';
}