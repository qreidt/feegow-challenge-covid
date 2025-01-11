<?php

namespace App\Repositories;

use app\Dtos\Vaccine\CreateVaccineDto;
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
            ->map(fn(array $vaccine) => (new Vaccine())->forceFill($vaccine));
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

    public function createVaccine(CreateVaccineDto $dto): Vaccine
    {
        $vaccine = Vaccine::create($dto->toArray());
        $this->updateVaccinesCache();

        return $vaccine;
    }
}
