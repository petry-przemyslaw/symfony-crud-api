<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use InvalidArgumentException;

class PhoneNumberAlreadyExistsException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('phone number already exists');
    }
}