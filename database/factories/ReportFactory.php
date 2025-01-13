<?php

namespace Database\Factories;

use App\Enums\ReportType;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'name' => 'Relatório de Vacinação',
            'type' => ReportType::CSV,
            'file_path' => '/reports/1/0000-00-00 00-00-Relatório-vacinação.csv',
            'ready_at' => Carbon::now(),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
