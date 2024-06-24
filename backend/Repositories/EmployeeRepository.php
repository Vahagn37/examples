<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Employee::class);
    }

    public function getBarbers()
    {
        return $this->getAll(null, ['is_barber' => 1]);
    }
}
