<?php

namespace App\Http\Controllers;

use App\Dtos\Vaccine\CreateVaccineDto;
use App\Services\VaccineService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class VaccineController extends Controller
{
    public function __construct(
        private readonly VaccineService $service,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $vaccines = $this->service->listVaccines();

        return Inertia::render('Vaccines/VaccinesListPage', compact('vaccines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $dto = CreateVaccineDto::validateFromArray(request()->all());
        $vaccine = $this->service->createVaccine($dto);

        return redirect()->route('vaccines.show', [$vaccine->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
