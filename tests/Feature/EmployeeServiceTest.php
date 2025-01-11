<?php

use App\Dtos\Employee\CreateEmployeeDto;
use App\Models\Employee;
use App\Services\EmployeeService;

it('saves an employee to the database', function () {
    $service = new EmployeeService();
    $employee_data = Employee::factory()->make();

    $dto = new CreateEmployeeDto(
        name: $employee_data->name,
        cpf: $employee_data->cpf,
        birth_date: $employee_data->birth_date,
        has_comorbidity: $employee_data->has_comorbidity,
    );

    $employee = $service->createEmployee($dto);

    expect($employee->toArray())->toEqual([
        'id' => $employee->id,
        ...$employee_data->toArray(),
    ]);
})->only();
