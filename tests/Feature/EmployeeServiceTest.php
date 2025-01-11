<?php

use App\Dtos\Employee\CreateEmployeeDto;
use App\Dtos\Employee\UpdateEmployeeDto;
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
    $employee = Employee::find($employee->id);

    expect($employee->toArray())->toEqual([
        'id' => $employee->id,
        ...$employee_data->toArray(),
    ]);
});

it('updates an employee in the database', function () {
    $service = new EmployeeService();
    $employee = Employee::factory()->create();
    $new_employee_data = Employee::factory()->make();

    $dto = new UpdateEmployeeDto(
        name: $new_employee_data->name,
        birth_date: $new_employee_data->birth_date,
        has_comorbidity: $new_employee_data->has_comorbidity,
    );

    $service->updateEmployee($employee, $dto);
    $updated_employee = Employee::find($employee->id);

    expect($updated_employee->toArray())->toEqual([
        ...$new_employee_data->toArray(),
        'id' => $employee->id,
        'cpf' => $employee->cpf,
    ]);
});

it('soft deletes an employee in the database', function () {
    $service = new EmployeeService();
    $employee = Employee::factory()->create();

    $service->softDeleteEmployee($employee);

    \Pest\Laravel\assertDatabaseHas('employees', [
        'id' => $employee->id,
        'deleted_at' => now(),
    ]);
});

it('recovers soft deleted employee in the database', function () {
    $service = new EmployeeService();
    $employee = Employee::factory()->create();

    $service->softDeleteEmployee($employee);
    $service->recoverEmployee($employee->id);

    \Pest\Laravel\assertDatabaseHas('employees', [
        'id' => $employee->id,
        'deleted_at' => null,
    ]);
});
