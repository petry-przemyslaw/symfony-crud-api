<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;
use Exception;

class EmployeeNotExistException extends Exception implements NotFoundException
{
    protected $message = 'employee not exist';
}