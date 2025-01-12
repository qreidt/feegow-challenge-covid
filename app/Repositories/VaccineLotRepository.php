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
        return "vaccine:$vaccine_id:lots";
    }

    /**
     * Gerar a chave no padrão de busca para lotes pelo id interno do lote
     *
     * @param int $vaccine_id
     * @param int $vaccine_lot_id
     * @return string
     */
    private static function getVaccineLotByIdKey(int $vaccine_id, int $vaccine_lot_id): string
    {
        return "vaccine:$vaccine_id:lots:id:$vaccine_lot_id";
    }

    /**
     * Gerar a chave no padrão de busca para lotes pelo id externo do lote
     *
     * @param int $vaccine_id
     * @param string $lot_id
     * @return string
     */
    private static function getVaccineLotByLotIdKey(int $vaccine_id, string $lot_id): string
    {
        return "vaccine:$vaccine_id:lots:lot_id:$lot_id";
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

        if (!$cache) {
            return null;
        }

        return collect($cache)
            ->map(fn(array $lot) => (new VaccineLot())->newInstance($lot, true)
                ->forceFill($lot)->syncOriginal());
    }

    /**
     * Buscar um lote de vacina por ID em cache ou no banco de dados
     *
     * @param int $vaccine_id
     * @param int $vaccine_lot_id
     * @return VaccineLot|null
     */
    public function findVaccineLotById(int $vaccine_id, int $vaccine_lot_id): ?VaccineLot
    {
        if ($cache = Cache::get(static::getVaccineLotByIdKey($vaccine_id, $vaccine_lot_id))) {
            return (new VaccineLot())->newInstance($cache, true)->forceFill($cache)->syncOriginal();
        }

        $vaccine_lot = VaccineLot::query()
            ->where('vaccine_id', $vaccine_id)
            ->find($vaccine_lot_id);

        if (!$vaccine_lot) {
            return null;
        }

        Cache::put(static::getVaccineLotByIdKey($vaccine_id, $vaccine_lot_id), $vaccine_lot->toArray());
        return $vaccine_lot;
    }

    /**
     * Buscar um lote de vacina pelo ID externo do lote em cache ou no banco de dados
     *
     * @param int $vaccine_id
     * @param string $lot_id
     * @return VaccineLot|null
     */
    public function findVaccineLotByLotId(int $vaccine_id, string $lot_id): ?VaccineLot
    {
        if ($cache = Cache::get(static::getVaccineLotByLotIdKey($vaccine_id, $lot_id))) {
            return (new VaccineLot())->newInstance($cache, true)->forceFill($cache)->syncOriginal();
        }

        $vaccine_lot = VaccineLot::query()
            ->where('vaccine_id', $vaccine_id)
            ->firstWhere('lot_id', $lot_id);

        if (!$vaccine_lot) {
            return null;
        }

        Cache::put(static::getVaccineLotByIdKey($vaccine_id, $vaccine_lot_id), $vaccine_lot->toArray());
        return $vaccine_lot;
    }

    /**
     * Criar um novo registro de lote de vacina no banco de dados. Atualiza caches
     *
     * @param CreateVaccineLotDto $dto
     * @return VaccineLot
     */
    public function createVaccineLot(CreateVaccineLotDto $dto): VaccineLot
    {
        $vaccine_lot = VaccineLot::create($dto->toArray());
        $this->updateVaccineLotItemCache($vaccine_lot);

        return $vaccine_lot;
    }

    /**
     * Atualizar o registro de um lote de vacina no banco de dados. Atualiza caches
     *
     * @param VaccineLot $vaccine_lot
     * @param UpdateVaccineLotDto $dto
     * @return VaccineLot
     */
    public function updateVaccineLot(VaccineLot $vaccine_lot, UpdateVaccineLotDto $dto): VaccineLot
    {
        $vaccine_lot->forceFill($dto->toArray());
        $vaccine_lot->save();

        $this->updateVaccineLotItemCache($vaccine_lot);

        return $vaccine_lot;
    }

    /**
     * Remover o registro de um lote de vacina no banco de dados. Atualiza caches
     *
     * @param VaccineLot $vaccine_lot
     * @return void
     */
    public function deleteVaccineLot(VaccineLot $vaccine_lot): void
    {
        $vaccine_lot->delete();
        $this->updateVaccineLotItemCache($vaccine_lot->vaccine_id);

        Cache::forget(static::getVaccineLotByIdKey($vaccine_lot->vaccine_id, $vaccine_lot->id));
        Cache::forget(static::getVaccineLotByLotIdKey($vaccine_lot->vaccine_id, $vaccine_lot->lot_id));
    }

    /**
     * Atualizar caches de lotes de vacina
     *
     * @param VaccineLot $vaccine_lot
     * @return void
     */
    public function updateVaccineLotItemCache(VaccineLot $vaccine_lot): void
    {
        $this->updateVaccineLotListCache($vaccine_lot->vaccine_id);
        Cache::put(static::getVaccineLotByIdKey($vaccine_lot->vaccine_id, $vaccine_lot->id), $vaccine_lot->toArray());
        Cache::put(static::getVaccineLotByLotIdKey($vaccine_lot->vaccine_id, $vaccine_lot->lot_id), $vaccine_lot->toArray());
    }

    /**
     * Atualizar cache da lista de lotes de vacina
     *
     * @param int $vaccine_id
     * @return void
     */
    public function updateVaccineLotListCache(int $vaccine_id): void
    {
        $vaccine_lots = $this->getVaccineLotsFromDatabase($vaccine_id);
        Cache::put(static::getVaccineLotKey($vaccine_id), $vaccine_lots->toArray());
    }
}
