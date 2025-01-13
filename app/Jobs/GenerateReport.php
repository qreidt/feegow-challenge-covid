<?php

namespace App\Jobs;

use App\Enums\ReportType;
use App\Models\Employee;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class GenerateReport implements ShouldQueue
{
    use Queueable;

    private const separator = ',';

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Report $report)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = $this->getFilePath();

        $this->report->name = 'Relatório de Vacinação';
        $this->report->type = ReportType::CSV;
        $this->report->file_path = $path;

        // Cabeçalho + Excel UTF-8 BOM
        $header = "\xEF\xBB\xBF" . join(static::separator, ['Nome', 'CPF']);
        Storage::put($path, $header);

        $this->writeEmployees();

        $this->report->ready_at = now();
        $this->report->save();
    }

    /**
     * Gerar nome do arquivo
     *
     * @return string
     */
    private function getFileName(): string
    {
        $date = now()->format('Y-m-d H-i');
        return "$date-Relatório-vacinação.csv";
    }

    /**
     * Gerar caminho interno a ser utilizado no Laravel
     *
     * @return string
     */
    private function getFilePath(): string
    {
        $file_name = $this->getFileName();
        return "/reports/{$this->report->id}/$file_name";
    }

    /**
     * Ler dados de funcionários em bateladas e escrever no arquivo csv
     *
     * @return void
     */
    private function writeEmployees(): void
    {
        $this->getEmployees()->chunk(1000)->each(function (LazyCollection $chunk) {
            $data = $chunk->map(function (Employee $employee) {
                $employee->anonimizeCpf();

                return join(static::separator, [$employee->name, $employee->cpf]);
            })->join("\n");

            Storage::append($this->getFilePath(), $data);
        });
    }

    /**
     * Gerar uma collection que limita a quantidade de funcionários ativos
     *
     * @return LazyCollection
     */
    private function getEmployees(): LazyCollection
    {
        return Employee::query()
            ->select(['name', 'cpf'])
            ->whereDoesntHave('employeeVaccines')
            ->lazyById();
    }

    /**
     * Disparar evento para notificar finalização do processo do relatório
     *
     * @return void
     */
    public function afterCommit()
    {
        //
    }
}
