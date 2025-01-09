<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vaccine;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->seedVaccines();
    }

    /**
     * Seed main vaccines in the database
     */
    private function seedVaccines(): void
    {
        $vaccines = [
            ['name' => 'Pfizer-BioNTech', 'slug' => 'PFIZER'],
            ['name' => 'Moderna', 'slug' => 'MODERNA'],
            ['name' => 'Johnson & Johnson', 'slug' => 'J&J'],
            ['name' => 'AstraZeneca', 'slug' => 'ASTRAZENECA'],
            ['name' => 'Sinovac', 'slug' => 'SINOVAC'],
        ];

        Vaccine::insert($vaccines);
    }
}
