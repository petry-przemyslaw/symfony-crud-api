<?php

declare(strict_types=1);

namespace App\Employee\Application\Service;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Application\DTO\CreateEmployeeRequest;
use App\Employee\Application\DTO\CreateEmployeeResponse;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Employee\Domain\ValueObject\Email;
use App\Employee\Domain\ValueObject\FirstName;
use App\Employee\Domain\ValueObject\LastName;
use App\Employee\Domain\ValueObject\PhoneNumber;

readonly class CreateEmployeeHandler
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
    }

    public function handle(CreateEmployeeRequest $request): CreateEmployeeResponse
    {
        return new CreateEmployeeResponse($this->repository->save(
            new Employee(
                null,
                new CompanyId($request->companyId),
                new FirstName($request->firstName),
                new LastName($request->lastName),
                new Email($request->email),
                $request->phoneNumber !== null ? new PhoneNumber($request->phoneNumber) : null
            )
        )->value);
    }
}