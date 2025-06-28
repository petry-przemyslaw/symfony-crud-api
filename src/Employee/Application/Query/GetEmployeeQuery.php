<?php

declare(strict_types=1);

namespace App\Employee\Application\Query;

use App\Employee\Application\DTO\EmployeeCompanyRequestQuery;
use App\Employee\Domain\Entity\EmployeeView;
use App\Employee\Domain\Exception\EmployeeNotExistException;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use Exception;

readonly class GetEmployeeQuery
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository)
    {
    }

    /**
     * @throws Exception
     * @throws EmployeeNotExistException
     */
    public function query(EmployeeCompanyRequestQuery $request): EmployeeView
    {
        $employee = $this->employeeRepository->findByCompanyIdAndEmployeeId(
            $request->companyId,
            $request->employeeId
        );
        if ($employee === null) {
            throw new EmployeeNotExistException;
        }
        return $employee;
    }
}