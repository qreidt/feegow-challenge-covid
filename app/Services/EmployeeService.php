<?php

namespace App\Services;

use App\Dtos\Employee\CreateEmployeeDto;
use App\Models\Employee;

class EmployeeService
{

    public function createEmployee(CreateEmployeeDto $dto): Employee
    {
        return Employee::create($dto->toArray());
    }
}
