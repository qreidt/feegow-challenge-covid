<?php

namespace App\Repositories;

use App\Models\Vaccine;
use App\Models\VaccineLot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class VaccineLotRepository
{

    /**
     * Chave para valores de cada lista de lotes de vacina
     *
     * @param Vaccine $vaccine
     * @return string
     */
    private static function getVaccineLotKey(Vaccine $vaccine): string
    {
        return `vacine:{$vaccine->id}:lots`;
    }

    /**
     * Buscar lista de lotes existentes que pertencem à vacina provida armazenados
     * no banco de dados
     *
     * @param Vaccine $vaccine
     * @return Collection
     */
    public function getVaccineLotsFromDatabase(Vaccine $vaccine): Collection
    {
        return VaccineLot::query()
            ->whereBelongsTo($vaccine)
            ->get();
    }

    /**
     * Retornar lista de lotes existentes que percentem à vacina provida armazenada
     * em cache
     *
     * @param Vaccine $vaccine
     * @return Collection
     */
    public function getVaccineLotsCache(Vaccine $vaccine): ?Collection
    {
        $cache = Cache::get(static::getVaccineLotKey($vaccine));

        if (! $cache) {
            return null;
        }

        return collect($cache)
            ->map(fn(array $lot) => (new VaccineLot())->forceFill($lot));
    }

    /**
     * Atualizar cache da lista de lotes para a vacina provida
     *
     * @param Vaccine $vaccine
     * @return void
     */
    public function updateVaccineLotCache(Vaccine $vaccine): void
    {
        $vaccine_lots = $this->getVaccineLotsFromDatabase($vaccine);
        Cache::put(static::getVaccineLotKey($vaccine), $vaccine_lots->toArray());
    }
}
