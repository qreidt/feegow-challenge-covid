<?php

namespace App\Services;

use App\Models\Vaccine;
use App\Repositories\VaccineRepository;
use Illuminate\Support\Collection;

readonly class VaccineService
{

    public function __construct(private VaccineRepository $repository)
    {
    }

    /**
     * Lista de Vacinas armazenadas em cache
     *
     * @return Collection<Vaccine>
     */
    public function listVaccines(): Collection
    {
        $vaccines = $this->repository->getVaccinesCache();

        if ($vaccines === null) {
            $vaccines = $this->repository->getVaccinesFromDatabase();

            $this->repository->updateVaccinesCache($vaccines);
        }

        return $vaccines;
    }
}
