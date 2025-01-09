<?php

namespace Database\Factories;

use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VaccineFactory extends Factory
{
    protected $model = Vaccine::class;

    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
