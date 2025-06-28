<?php

declare(strict_types=1);

namespace App\Employee\Domain\Repository;

use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Domain\Collection\EmployeeViewCollection;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Entity\EmployeeView;
use App\Employee\Domain\ValueObject\EmployeeId;

interface EmployeeRepositoryInterface
{
    public function save(Employee $employee): EmployeeId;
    public function deleteByCompanyIdAndEmployeeId(CompanyId $companyId, EmployeeId $employeeId): void;
    public function findByCompanyIdAndEmployeeId(CompanyId $companyId, EmployeeId $employeeId): ?EmployeeView;
    public function findAllByCompanyId(CompanyId $companyId): EmployeeViewCollection;
}