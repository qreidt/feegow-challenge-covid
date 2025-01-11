<?php

use App\Dtos\Vaccine\CreateVaccineDto;
use App\Dtos\Vaccine\UpdateVaccineDto;
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

it('saves a vaccine to the database', function () {
    $repository = new VaccineRepository();
    $vaccine_data = Vaccine::factory()->make();

    $dto = new CreateVaccineDto(
        name: $vaccine_data->name,
        slug: $vaccine_data->slug,
    );

    $vaccine = $repository->createVaccine($dto);
    $vaccine = Vaccine::find($vaccine->id);

    expect($vaccine->toArray())->toEqual([
        'id' => $vaccine->id,
        ...$vaccine_data->toArray(),
    ]);
});



it('updates a vaccine in the database', function () {
    $repository = new VaccineRepository();
    $vaccine = Vaccine::factory()->create();
    $new_vaccine_data = Vaccine::factory()->make();

    $dto = new UpdateVaccineDto(
        name: $new_vaccine_data->name,
        slug: $new_vaccine_data->slug,
    );

    $repository->updateVaccine($vaccine, $dto);
    $updated_vaccine = Vaccine::find($vaccine->id);

    expect($updated_vaccine->toArray())->toEqual([
        'id' => $vaccine->id,
        ...$new_vaccine_data->toArray(),
    ]);
});



it('deletes a vaccine from the database', function () {
    $repository = new VaccineRepository();
    $vaccine = Vaccine::factory()->create();

    $repository->deleteVaccine($vaccine);

    \Pest\Laravel\assertDatabaseMissing('vaccines', [
        'id' => $vaccine->id,
    ]);
});
