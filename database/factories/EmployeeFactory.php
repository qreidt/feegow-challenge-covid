<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'cpf' => $this->faker->word(),
            'name' => $this->faker->name(),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'has_comorbidity' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
