<?php

declare(strict_types=1);

namespace App\Employee\Domain\Entity;

use App\Employee\Domain\ValueObject\Email;
use App\Employee\Domain\ValueObject\EmployeeId;
use App\Employee\Domain\ValueObject\FirstName;
use App\Employee\Domain\ValueObject\LastName;
use App\Employee\Domain\ValueObject\PhoneNumber;
use JsonSerializable;
use function array_filter;

readonly class EmployeeView implements JsonSerializable
{
    public function __construct(
        public EmployeeId $employeeId,
        public FirstName $firstName,
        public LastName $lastName,
        public Email $email,
        public ?PhoneNumber $phoneNumber
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'id' => $this->employeeId->value,
            'first_name' => $this->firstName->value,
            'last_name' => $this->lastName->value,
            'email' => $this->email->value,
            'phone_number' => $this->phoneNumber?->value
        ]);
    }
}