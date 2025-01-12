<?php

namespace App\Services;

use App\Dtos\Vaccine\CreateVaccineLotDto;
use App\Dtos\Vaccine\UpdateVaccineLotDto;
use App\Models\Vaccine;
use App\Models\VaccineLot;
use App\Repositories\VaccineLotRepository;
use Carbon\CarbonImmutable;
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
            $this->repository->updateVaccineLotListCache($vaccine->id);
        }

        return $vaccine_lots;
    }

    /**
     * Buscar um lote de vacina por ID
     *
     * @param int $vaccine_id
     * @param int $vaccine_lot_id
     * @return VaccineLot|null
     */
    public function findVaccineLotById(int $vaccine_id, int $vaccine_lot_id): ?VaccineLot
    {
        $this->repository->findVaccineLotById($vaccine_id, $vaccine_lot_id);
    }

    /**
     * Buscar um lote de vacina por ID externo da vacina
     *
     * @param int $vaccine_id
     * @param string $lot_id
     * @return VaccineLot|null
     */
    public function findVaccineLotByLotId(int $vaccine_id, string $lot_id): ?VaccineLot
    {
        $this->repository->findVaccineLotByLotId($vaccine_id, $lot_id);
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

    public function findOrCreateVaccineLot(int $vaccine_id, string $lot_id, CarbonImmutable $expiration_date): VaccineLot
    {
        if ($vaccine_lot = $this->findVaccineLotByLotId($vaccine_id, $lot_id)) {
            return $vaccine_lot;
        }

        return $this->createVaccineLot(new CreateVaccineLotDto(
            vaccine_id: $vaccine_id,lot_id: $lot_id, expiration_date: $expiration_date
        ));
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
