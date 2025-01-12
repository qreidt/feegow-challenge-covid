<?php

namespace App\Services;

use App\Dtos\Employee\CreateEmployeeVaccineDto;
use App\Dtos\Employee\UpdateEmployeeVaccineDto;
use App\Models\EmployeeVaccine;
use App\Models\VaccineLot;

readonly class EmployeeVaccineService
{
    public function __construct(private VaccineLotService $vaccineLotService)
    {
    }

    /**
     * Criar uma relação com dose de vacina e funcionário
     *
     * @param CreateEmployeeVaccineDto $dto
     * @return EmployeeVaccine
     */
    public function createEmployeeVaccine(CreateEmployeeVaccineDto $dto): EmployeeVaccine
    {
        $vaccine_lot = $this->vaccineLotService->findOrCreateVaccineLot(
            $dto->vaccine_id, $dto->lot_id, $dto->expiration_date);

        $employee_vaccine = new EmployeeVaccine($dto->toArray());
        $employee_vaccine->vaccine_lot_id = $vaccine_lot->id;

        $employee_vaccine->save();
        return $employee_vaccine;
    }

    /**
     * Atualizar uma dose de vacina do funcionário.
     *
     * @param EmployeeVaccine $employee_vaccine
     * @param UpdateEmployeeVaccineDto $dto
     * @return EmployeeVaccine
     */
    public function updateEmployeeVaccine(
        EmployeeVaccine $employee_vaccine,
        UpdateEmployeeVaccineDto $dto
    ): EmployeeVaccine
    {
        $vaccine_lot = $this->vaccineLotService->findVaccineLotById($dto->vaccine_id, $dto->vaccine_lot_id);
        $vaccine_lot->fill([
            'vaccine_id' => $dto->vaccine_id,
            'lot_id' => $dto->lot_id,
            'expiration_date' => $dto->expiration_date
        ]);

        if (! empty($vaccine_lot->getDirty())) {
            $vaccine_lot = $this->handleDirtyVaccineLot($vaccine_lot);
            $employee_vaccine->vaccine_lot_id = $vaccine_lot->id;
        }

        $employee_vaccine->fill($dto->toArray());
        $employee_vaccine->save();

        return $employee_vaccine;
    }

    /**
     * Lidar com alterações no lote da vacina
     *
     * @param VaccineLot $vaccine_lot
     * @return VaccineLot
     */
    private function handleDirtyVaccineLot(VaccineLot $vaccine_lot): VaccineLot
    {
        if ($vaccine_lot->isDirty('vaccine_id') || $vaccine_lot->isDirty('lot_id')) {
            return $this->vaccineLotService->findOrCreateVaccineLot(
                $vaccine_lot->vaccine_id,
                $vaccine_lot->lot_id,
                $vaccine_lot->expiration_date,
            );
        }

        $vaccine_lot->discardChanges();
        return $vaccine_lot;
    }

    /**
     * Remover o registro de uma dose do funcionário.
     *
     * @param EmployeeVaccine $employee_vaccine
     * @return void
     */
    public function deleteEmployeeVaccine(EmployeeVaccine $employee_vaccine): void
    {
        $employee_vaccine->delete();
    }
}
