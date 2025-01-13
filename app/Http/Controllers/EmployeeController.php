<?php

namespace App\Http\Controllers;

use App\Dtos\Employee\CreateEmployeeDto;
use App\Dtos\Employee\CreateEmployeeVaccineDto;
use App\Http\Requests\CreateEmployeeRequest;
use App\Models\Employee;
use App\Models\EmployeeVaccine;
use App\Services\EmployeeService;
use App\Services\EmployeeVaccineService;
use App\Services\VaccineLotService;
use App\Services\VaccineService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly VaccineService $vaccineService,
        private readonly VaccineLotService $vaccineLotService,
        private readonly EmployeeVaccineService $employeeVaccineService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $employees = Employee::query()
            ->with('employeeVaccines')
            ->paginate();

        $employees->transform(function (Employee $employee) {
            $employee->anonimizeCpf();

            $doses = $employee->employeeVaccines->groupBy('dose_number')
                ->map(fn(Collection $doses) => $doses->first())
                ->mapWithKeys(fn(EmployeeVaccine $employee_vaccine, int $i) => ["dose_$i" => $employee_vaccine]);

            $employee->unsetRelation('employeeVaccines');

            $doses->transform(function (EmployeeVaccine $employee_vaccine) {
                $vaccine_lot = $this->vaccineLotService->findVaccineLotById(
                    $employee_vaccine->vaccine_id, $employee_vaccine->vaccine_lot_id
                );

                return [
                    'vaccine_id' => $employee_vaccine->vaccine_id,
                    'vaccine_lot_id' => $employee_vaccine->vaccine_lot_id,
                    'dose_number' => $employee_vaccine->dose_number,
                    'lot_id' => $vaccine_lot->lot_id,
                    'expiration_date' => $vaccine_lot->expiration_date->toDateString(),
                    'applied_at' => $employee_vaccine->applied_at->toDateString(),
                ];
            });

            $employee->setAttribute('doses_count', $doses->count());
            foreach ($doses as $dose_key => $dose) {
                $employee->setAttribute($dose_key, $dose);
            }

            return $employee;
        });

        $vaccines = $this->vaccineService->listVaccines();

        return Inertia::render('Employees/EmployeesListPage', compact('employees', 'vaccines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request): RedirectResponse
    {
        $employee_dto = CreateEmployeeDto::validateFromArray($request->only([
            'name', 'cpf', 'birth_date', 'has_comorbidity',
        ]));

        DB::transaction(function () use ($request, $employee_dto) {
            $employee = $this->service->createEmployee($employee_dto);
            foreach (['dose_1', 'dose_2', 'dose_3'] as $i => $dose) {
                $vaccine_id = $request->input("$dose.vaccine_id");
                if (! $vaccine_id) {
                    continue;
                }

                $this->employeeVaccineService->createEmployeeVaccine(new CreateEmployeeVaccineDto(
                    employee_id: $employee->id,
                    vaccine_id: $request->input("$dose.vaccine_id"),
                    dose_number: $i + 1,
                    applied_at: CarbonImmutable::make($request->input("$dose.applied_at")),
                    lot_id: $request->input("$dose.lot_id"),
                    expiration_date: CarbonImmutable::make($request->input("$dose.expiration_date")),
                ));
            }
        });

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
