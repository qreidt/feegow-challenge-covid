<?php

use App\Models\Vaccine;
use App\Repositories\VaccineRepository;

it('retrieves a list of vaccines from the database', function () {
    $repository = new VaccineRepository();
    Vaccine::factory()->count(5)->create();

    $vaccines = $repository->getVaccinesFromDatabase();

    expect($vaccines->toArray())
        ->toEqual(Vaccine::all()->toArray());
});

it('updates the cache of vaccines', function () {
    $repository = new VaccineRepository();
    Vaccine::factory()->count(5)->create();

    expect($repository->getVaccinesCache())->toBe(null);
    $repository->updateVaccinesCache();

    expect($repository->getVaccinesCache()->toArray())
        ->toEqual(Vaccine::all()->toArray());
});

it('retrieves a list of vaccines from cache', function () {
    $repository = new VaccineRepository();
    Vaccine::factory()->count(5)->create();
    $vaccines = Vaccine::all();

    $repository->updateVaccinesCache($vaccines);
    $cached_vaccines = $repository->getVaccinesCache();

    expect($cached_vaccines->toArray())->toEqual($vaccines->toArray());
});
