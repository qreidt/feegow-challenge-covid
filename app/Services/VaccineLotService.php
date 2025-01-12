<?php

namespace App\Services;

use App\Models\Vaccine;
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
     * @return Collection
     */
    public function getVaccineLots(Vaccine $vaccine): Collection
    {
        $vaccine_lots = $this->repository->getVaccineLotsCache($vaccine);

        if (! $vaccine_lots) {
            $vaccine_lots = $this->repository->getVaccineLotsFromDatabase($vaccine);
            $this->repository->getVaccineLotsCache($vaccine);
        }

        return $vaccine_lots;
    }
}
