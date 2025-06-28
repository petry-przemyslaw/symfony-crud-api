<?php

declare(strict_types=1);

namespace App\Employee\Domain\Entity;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Domain\ValueObject\Email;
use App\Employee\Domain\ValueObject\EmployeeId;
use App\Employee\Domain\ValueObject\FirstName;
use App\Employee\Domain\ValueObject\LastName;
use App\Employee\Domain\ValueObject\PhoneNumber;

class Employee
{
    public function __construct(
        public ?EmployeeId $employeeId,
        public CompanyId $companyId,
        public FirstName $firstName,
        public LastName $lastName,
        public Email $email,
        public ?PhoneNumber $phoneNumber
    ) {
    }
}