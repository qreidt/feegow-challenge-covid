<?php

namespace App\Services;

use App\Dtos\Employee\CreateEmployeeDto;
use App\Dtos\Employee\UpdateEmployeeDto;
use App\Models\Employee;

readonly class EmployeeService
{

    /**
     * Armazena um novo usuário no banco de dados
     *
     * @param CreateEmployeeDto $dto
     * @return Employee
     */
    public function createEmployee(CreateEmployeeDto $dto): Employee
    {
        return Employee::create($dto->toArray());
    }

    /**
     * Atualiza os dados de um usuário no banco de dados
     *
     * @param Employee $employee
     * @param UpdateEmployeeDto $dto
     * @return Employee
     */
    public function updateEmployee(Employee $employee, UpdateEmployeeDto $dto): Employee
    {
        $employee->fill($dto->toArray());
        $employee->save();

        return $employee;
    }

    /**
     * Ativa o soft delete do usuário no banco de dados
     *
     * @param Employee $employee
     * @return Employee
     */
    public function softDeleteEmployee(Employee $employee): Employee
    {
        $employee->delete();
        return $employee;
    }

    /**
     * Remove o soft delete de um usuário no banco de dados
     *
     * @param int $id
     * @return Employee
     */
    public function recoverEmployee(int $id): Employee
    {
        $employee = Employee::withTrashed()
            ->findOrFail($id);

        $employee->restore();
        return $employee;
    }
}
