<?php

declare(strict_types=1);

namespace App\Employee\Application\Command;

use JsonSerializable;
use function array_filter;

readonly class UpdateEmployeeCommand implements JsonSerializable
{
    public function __construct(
        public int $companyId,
        public int $employeeId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public ?string $phoneNumber
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'employee_id' => $this->employeeId,
            'company_id' => $this->companyId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber
        ]);
    }
}