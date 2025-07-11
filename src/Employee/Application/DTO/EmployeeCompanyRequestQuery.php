<?php

declare(strict_types=1);

namespace App\Employee\Application\DTO;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Domain\ValueObject\EmployeeId;
use JsonSerializable;

readonly class EmployeeCompanyRequestQuery implements JsonSerializable
{
    public function __construct(public CompanyId $companyId, public EmployeeId $employeeId)
    {
    }

    /**
     * @return array<string, int>
     */
    public function jsonSerialize(): array
    {
        return [
          'company_id' => $this->companyId->value,
          'employee_id' => $this->employeeId->value
        ];
    }
}