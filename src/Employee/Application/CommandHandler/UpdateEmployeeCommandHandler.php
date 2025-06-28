<?php

declare(strict_types=1);

namespace App\Employee\Application\CommandHandler;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Application\Command\UpdateEmployeeCommand;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Employee\Domain\ValueObject\Email;
use App\Employee\Domain\ValueObject\EmployeeId;
use App\Employee\Domain\ValueObject\FirstName;
use App\Employee\Domain\ValueObject\LastName;
use App\Employee\Domain\ValueObject\PhoneNumber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateEmployeeCommandHandler
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
    }

    public function __invoke(UpdateEmployeeCommand $command): void
    {
        $this->repository->save(
            new Employee(
                new EmployeeId($command->employeeId),
                new CompanyId($command->companyId),
                new FirstName($command->firstName),
                new LastName($command->lastName),
                new Email($command->email),
                $command->phoneNumber ? new PhoneNumber($command->phoneNumber) : null
            )
        );
    }
}