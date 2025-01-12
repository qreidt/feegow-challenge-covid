<?php

namespace App\Repositories;

use App\Dtos\Vaccine\CreateVaccineLotDto;
use App\Dtos\Vaccine\UpdateVaccineLotDto;
use App\Models\Vaccine;
use App\Models\VaccineLot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class VaccineLotRepository
{

    /**
     * Chave para valores de cada lista de lotes de vacina
     *
     * @param int $vaccine_id
     * @return string
     */
    private static function getVaccineLotKey(int $vaccine_id): string
    {
        return `vacine:{$vaccine->id}:lots`;
    }

    /**
     * Buscar lista de lotes existentes que pertencem à vacina provida armazenados
     * no banco de dados
     *
     * @param int $vaccine_id
     * @return Collection
     */
    public function getVaccineLotsFromDatabase(int $vaccine_id): Collection
    {
        return VaccineLot::query()
            ->where('vaccine_id', '=', $vaccine_id)
            ->get();
    }

    /**
     * Retornar lista de lotes existentes que percentem à vacina provida armazenada
     * em cache
     *
     * @param int $vaccine_id
     * @return null|Collection
     */
    public function getVaccineLotsCache(int $vaccine_id): ?Collection
    {
        $cache = Cache::get(static::getVaccineLotKey($vaccine_id));

        if (! $cache) {
            return null;
        }

        return collect($cache)
            ->map(fn(array $lot) => (new VaccineLot())->forceFill($lot));
    }

    /**
     * Atualizar cache da lista de lotes para a vacina provida
     *
     * @param int $vaccine_id
     * @return void
     */
    public function updateVaccineLotCache(int $vaccine_id): void
    {
        $vaccine_lots = $this->getVaccineLotsFromDatabase($vaccine_id);
        Cache::put(static::getVaccineLotKey($vaccine_id), $vaccine_lots->toArray());
    }

    public function createVaccineLot(CreateVaccineLotDto $dto): VaccineLot
    {
        $vaccine_lot = VaccineLot::create($dto->toArray());
        $this->updateVaccineLotCache($dto->vaccine_id);

        return $vaccine_lot;
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
        $vaccine_lot->forceFill($dto->toArray());
        $vaccine_lot->save();

        $this->updateVaccineLotCache($vaccine_lot->vaccine_id);

        return $vaccine_lot;
    }

    /**
     * Remover o registro de um lote de vacina no banco de dados
     *
     * @param VaccineLot $vaccine_lot
     * @return void
     */
    public function deleteVaccineLot(VaccineLot $vaccine_lot): void
    {
        $vaccine_lot->delete();
        $this->updateVaccineLotCache($vaccine_lot->vaccine_id);
    }
}
