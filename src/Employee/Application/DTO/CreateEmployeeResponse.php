<?php

declare(strict_types=1);

namespace App\Employee\Application\DTO;

use JsonSerializable;

readonly class CreateEmployeeResponse implements JsonSerializable
{
    public function __construct(public int $employeeId)
    {
    }

    /**
     * @return array<string, int>
     */
    public function jsonSerialize(): array
    {
        return [
            'employee_id' => $this->employeeId
        ];
    }
}