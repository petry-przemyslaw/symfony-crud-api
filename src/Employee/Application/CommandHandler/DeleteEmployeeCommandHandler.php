<?php

declare(strict_types=1);

namespace App\Employee\Application\CommandHandler;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Application\Command\DeleteEmployeeCommand;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Employee\Domain\ValueObject\EmployeeId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteEmployeeCommandHandler
{

    public function __construct(private EmployeeRepositoryInterface $employeeRepository)
    {
    }

    public function __invoke(DeleteEmployeeCommand $command): void
    {
        $this->employeeRepository->deleteByCompanyIdAndEmployeeId(
            new CompanyId($command->companyId),
            new EmployeeId($command->employeeId)
        );
    }
}