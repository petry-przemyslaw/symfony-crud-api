<?php

declare(strict_types=1);

namespace App\Company\Domain\Exception;

use InvalidArgumentException;

class NipAlreadyExistsException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('nip already exists');
    }
}