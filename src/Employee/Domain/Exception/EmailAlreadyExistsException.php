<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use InvalidArgumentException;

class EmailAlreadyExistsException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('email already exists');
    }
}