<?php

namespace App\Services;

use App\Dtos\Vaccine\CreateVaccineLotDto;
use App\Dtos\Vaccine\UpdateVaccineLotDto;
use App\Models\Vaccine;
use App\Models\VaccineLot;
use App\Repositories\VaccineLotRepository;
use Illuminate\Support\Collection;

readonly class VaccineLotService
{
    public function __construct(private VaccineLotRepository $repository)
    {
    }

    /**
     * Buscar lista de lotes da vacina provida em cache. Caso o cache não seja encontrado,
     * buscar no banco de dados e atualizar o cache.
     *
     * @param Vaccine $vaccine
     * @return Collection<VaccineLot>
     */
    public function getVaccineLots(Vaccine $vaccine): Collection
    {
        $vaccine_lots = $this->repository->getVaccineLotsCache($vaccine->id);

        if (! $vaccine_lots) {
            $vaccine_lots = $this->repository->getVaccineLotsFromDatabase($vaccine->id);
            $this->repository->updateVaccineLotCache($vaccine->id);
        }

        return $vaccine_lots;
    }

    /**
     * Armazenar um novo lote de vacina no banco de dados
     *
     * @param CreateVaccineLotDto $dto
     * @return VaccineLot
     */
    public function createVaccineLot(CreateVaccineLotDto $dto): VaccineLot
    {
        return $this->repository->createVaccineLot($dto);
    }

    /**
     * Atualizar o registro de um lote de vacina no banco de dados
     *
     * @param VaccineLot $vaccine_lot
     * @param UpdateVaccineLotDto $dto
     * @return VaccineLot
     */
    public function updateVaccineLot(VaccineLot $vaccine_lot, UpdateVaccineLotDto $dto): VaccineLot
    {
        return $this->repository->updateVaccineLot($vaccine_lot, $dto);
    }

    /**
     * Remover o registro de um lote de vacina no banco de dados
     *
     * @param VaccineLot $vaccine_lot
     * @return void
     */
    public function deleteVaccineLot(VaccineLot $vaccine_lot): void
    {
        $this->repository->deleteVaccineLot($vaccine_lot);
    }
}
