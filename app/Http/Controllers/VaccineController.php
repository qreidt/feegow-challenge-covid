<?php

namespace App\Http\Controllers;

use App\Dtos\Vaccine\CreateVaccineDto;
use App\Dtos\Vaccine\UpdateVaccineDto;
use App\Services\VaccineLotService;
use App\Services\VaccineService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VaccineController extends Controller
{
    public function __construct(
        private readonly VaccineService $service,
        private readonly VaccineLotService $vaccineLotService,
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
        $vaccine = $this->service->findVaccineById($id);
        if (! $vaccine) {
            throw new NotFoundHttpException();
        }

        $vaccine_lots = $this->vaccineLotService->getPaginatedVaccineLots($vaccine, request('page', 1))
            ->onEachSide(1);

        return Inertia::render('Vaccines/VaccineShowPage', compact('vaccine', 'vaccine_lots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id): RedirectResponse
    {
        $vaccine = $this->service->findVaccineById($id);
        if (! $vaccine) {
            throw new NotFoundHttpException();
        }

        $dto = UpdateVaccineDto::validateFromArray(request()->all());
        $this->service->updateVaccine($vaccine, $dto);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $vaccine = $this->service->findVaccineById($id);
        if (! $vaccine) {
            throw new NotFoundHttpException();
        }

        $this->service->deleteVaccine($vaccine);
        return redirect()->route('vaccines.index');
    }
}
