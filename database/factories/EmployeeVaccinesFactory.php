<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeVaccine;
use App\Models\Vaccine;
use App\Models\VaccineLot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeVaccinesFactory extends Factory
{
    protected $model = EmployeeVaccine::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'vaccine_id' => Vaccine::factory(),
            'vaccine_lot_id' => VaccineLot::factory(),

            'dose_number' => $this->faker->randomFloat(0, 1, 3),
            'applied_at' => $this->faker->dateTimeBetween('-2 years'),
        ];
    }
}
