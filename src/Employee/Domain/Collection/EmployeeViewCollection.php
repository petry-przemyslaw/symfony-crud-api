<?php

declare(strict_types=1);

namespace App\Employee\Domain\Collection;

use App\Employee\Domain\Entity\EmployeeView;
use Countable;
use function count;

class EmployeeViewCollection implements Countable
{
    /**
     * @var EmployeeView[]
     */
    private array $collection = [];

    public function add(EmployeeView $employeeView): void
    {
        $this->collection[] = $employeeView;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function getAll(): array
    {
        return $this->collection;
    }
}