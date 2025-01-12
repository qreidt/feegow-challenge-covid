<?php

namespace App\Repositories;

use app\Dtos\Vaccine\CreateVaccineDto;
use App\Dtos\Vaccine\UpdateVaccineDto;
use App\Models\Vaccine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class VaccineRepository
{
    const vaccine_list_key = 'vaccines';

    /**
     * Retornar lista de vacinas armazenadas no banco de dados
     *
     * @return Collection<Vaccine>
     */
    public function getVaccinesFromDatabase(): Collection
    {
        return Vaccine::all();
    }

    /**
     * Retornar listas de vacinas armazenadas em cache
     *
     * @return ?Collection<Vaccine>
     */
    public function getVaccinesCache(): ?Collection
    {
        $cache = Cache::get(static::vaccine_list_key);
        if (! $cache) {
            return null;
        }

        return collect($cache)
            ->map(
                fn(array $vaccine) => (new Vaccine())->newInstance($vaccine, true)
                    ->forceFill($vaccine)
                    ->syncOriginal());
    }

    /**
     * Atualizar cache de lista de vacinas
     *
     * @param Collection<Vaccine> $vaccines
     * @return void
     */
    public function updateVaccinesCache(): void
    {
        $vaccines = $this->getVaccinesFromDatabase();
        Cache::put(static::vaccine_list_key, $vaccines->toArray());
    }

    /**
     * Armazena uma nova vacina e atualiza lista em cache de vacinas
     *
     * @param CreateVaccineDto $dto
     * @return Vaccine
     */
    public function createVaccine(CreateVaccineDto $dto): Vaccine
    {
        $vaccine = Vaccine::create($dto->toArray());
        $this->updateVaccinesCache();

        return $vaccine;
    }

    /**
     * Atualiza os dados de uma vacina e atualiza lista em cache de vacinas
     *
     * @param Vaccine $vaccine
     * @param UpdateVaccineDto $dto
     * @return Vaccine
     */
    public function updateVaccine(Vaccine $vaccine, UpdateVaccineDto $dto): Vaccine
    {
        $vaccine->fill($dto->toArray());
        $vaccine->save();

        $this->updateVaccinesCache();

        return $vaccine;
    }

    /**
     * Remove uma vacina do banco de dados e da lista de vacinas
     *
     * @param Vaccine $vaccine
     * @return void
     */
    public function deleteVaccine(Vaccine $vaccine): void
    {
        $vaccine->delete();
        $this->updateVaccinesCache();
    }
}
