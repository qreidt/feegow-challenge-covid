<?php

namespace App\Http\Controllers;

use App\Services\VaccineService;
use Illuminate\Http\Request;
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
    public function store()
    {
        //
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
