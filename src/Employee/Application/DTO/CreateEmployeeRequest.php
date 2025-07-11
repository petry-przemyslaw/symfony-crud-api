<?php

declare(strict_types=1);

namespace App\Employee\Application\DTO;

use JsonSerializable;

use function array_filter;

readonly class CreateEmployeeRequest implements JsonSerializable
{
    public function __construct(
        public int $companyId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public ?string $phoneNumber
    ) {
    }

    /**
     * @return array<string, int|string|null>
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'companyId' => $this->companyId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber
        ]);
    }
}
