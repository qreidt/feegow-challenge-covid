<?php

namespace Database\Factories;

use App\Models\Vaccine;
use App\Models\VaccineLot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VaccineLotFactory extends Factory
{
    protected $model = VaccineLot::class;

    public function definition(): array
    {
        return [
            'vaccine_id' => Vaccine::factory(),

            'lot_id' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}-[0-9]{4}'),
            'expiration_date' => $this->faker->dateTimeBetween('+4 days', '+6 months'),
        ];
    }
}
