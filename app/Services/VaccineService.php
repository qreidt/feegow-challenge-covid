<?php

namespace App\Services;

use App\Dtos\Vaccine\CreateVaccineDto;
use App\Dtos\Vaccine\UpdateVaccineDto;
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

    /**
     * Adicionar uma nova vacina a uma lista de vacinas
     *
     * @param CreateVaccineDto $dto
     * @return Vaccine
     */
    public function createVaccine(CreateVaccineDto $dto): Vaccine
    {
        return $this->repository->createVaccine($dto);
    }

    /**
     * Atualizar dados de uma vacina
     *
     * @param Vaccine $vaccine
     * @param UpdateVaccineDto $dto
     * @return Vaccine
     */
    public function updateVaccine(Vaccine $vaccine, UpdateVaccineDto $dto): Vaccine
    {
        return $this->repository->updateVaccine($vaccine, $dto);
    }

    /**
     * Remove os dados de uma vacina
     *
     * @param Vaccine $vaccine
     * @return void
     */
    public function deleteVaccine(Vaccine $vaccine): void
    {
        $this->repository->deleteVaccine($vaccine);
    }
}
