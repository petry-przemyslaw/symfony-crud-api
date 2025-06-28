<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use InvalidArgumentException;

class PhoneNumberAlreadyExistsException extends InvalidArgumentException
{
    protected $message = 'phone number already exists';
}