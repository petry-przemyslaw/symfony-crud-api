<?php

declare(strict_types=1);

namespace App\Employee\Application\Query;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Domain\Collection\EmployeeViewCollection;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;

readonly class GetAllEmployeeQuery
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
    }

    public function query(CompanyId $companyId): EmployeeViewCollection
    {
        return $this->repository->findAllByCompanyId($companyId);
    }
}