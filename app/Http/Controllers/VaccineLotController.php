<?php

namespace App\Http\Controllers;

use App\Dtos\Vaccine\UpdateVaccineLotDto;
use App\Services\VaccineLotService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VaccineLotController extends Controller
{
    public function __construct(private readonly VaccineLotService $service)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $vaccine_id, int $id): RedirectResponse
    {
        $vaccine_lot = $this->service->findVaccineLotById($vaccine_id, $id);
        if (! $vaccine_lot) {
            throw new NotFoundHttpException();
        }

        $dto = UpdateVaccineLotDto::validateFromArray(request()->all());
        $this->service->updateVaccineLot($vaccine_lot, $dto);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $vaccine_id, int $id)
    {
        $vaccine_lot = $this->service->findVaccineLotById($vaccine_id, $id);
        if (! $vaccine_lot) {
            throw new NotFoundHttpException();
        }

        $this->service->deleteVaccineLot($vaccine_lot);
        return redirect()->back();
    }
}
