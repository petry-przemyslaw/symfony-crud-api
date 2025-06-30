<?php

declare(strict_types=1);

namespace App\Company\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;
use Exception;

class CompanyNotExistException extends Exception implements NotFoundException
{
    public function __construct()
    {
        parent::__construct('company not exist');
    }
}
