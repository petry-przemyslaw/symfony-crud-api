<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;
use Exception;

class EmployeeNotExistException extends Exception implements NotFoundException
{
    public function __construct()
    {
        parent::__construct('employee not exist');
    }

}