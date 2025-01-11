<?php

namespace App\Services;

use App\Dtos\Employee\CreateEmployeeDto;
use App\Dtos\Employee\UpdateEmployeeDto;
use App\Models\Employee;

class EmployeeService
{

    public function createEmployee(CreateEmployeeDto $dto): Employee
    {
        return Employee::create($dto->toArray());
    }

    public function updateEmployee(Employee $employee, UpdateEmployeeDto $dto): Employee
    {
        $employee->fill($dto->toArray());
        $employee->save();

        return $employee;
    }
}
