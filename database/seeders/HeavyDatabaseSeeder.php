<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeVaccine;
use App\Models\Vaccine;
use App\Models\VaccineLot;
use App\Repositories\VaccineLotRepository;
use App\Repositories\VaccineRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeavyDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Padrão
        (new DatabaseSeeder())->run();

        $this->seedEmployees();
        $this->seedEmployeeVaccines();
        $this->triggerCaches();
    }

    private function seedEmployees(): void
    {
        $count = 0;
        echo "Seeding Employees...\n";
        while ($count < 100_000) {
            $employees = Employee::factory(2000)->make()->toArray();
            DB::table('employees')->insert($employees);

            $count = Employee::count();
            echo "Seeding Employees [".number_format($count)."/100,000] \n";
        }
    }

    private function seedEmployeeVaccines(): void
    {
        $vaccines = Vaccine::all();

        echo "Seeding Employee Vaccines...\n";
        Employee::lazyById()->each(function (Employee $employee) use ($vaccines) {
            $luck = fake()->randomFloat(1, max: 100);

            $vaccine = fake()->randomElement($vaccines);
            $base_state = [
                'employee_id' => $employee->id,
                'vaccine_id' => $vaccine->id,
            ];

            if ($luck <= 10) {
                $factory = EmployeeVaccine::factory(state: $base_state)->count(3);
            } else if ($luck <= 30) {
                $factory = EmployeeVaccine::factory(state: $base_state)->count(2);
            } else if ($luck <= 40) {
                $factory = EmployeeVaccine::factory(state: $base_state)->count(1);
            } else {
                return;
            }

            $data = $factory->sequence(
                ['dose_number' => 1],
                ['dose_number' => 2],
                ['dose_number' => 3],
            )->make($base_state)->toArray();

            DB::table('employee_vaccines')->insert($data);
            echo "Seeding Employee Vaccines [".number_format($employee->id)."/100,000] \n";
        });
    }

    private function triggerCaches(): void
    {
        $vaccineLotRepository = new VaccineLotRepository();
        VaccineLot::select(['id', 'vaccine_id'])
            ->lazyById()->each(function (VaccineLot $vaccineLot) use ($vaccineLotRepository) {
                $vaccineLotRepository->findVaccineLotById($vaccineLot->vaccine_id, $vaccineLot->id);
            });
    }
}
