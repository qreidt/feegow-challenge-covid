<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers;

if (app()->isLocal() && file_exists(app_path('Http/Controllers/DevController.php'))) {
    Route::get('/dev', Controllers\DevController::class);
}

Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum', 'verified',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('/vaccines', Controllers\VaccineController::class);
    Route::name('vaccines.')->prefix('/vaccines/{vaccine}')->group(function () {
        Route::resource('/lots', Controllers\VaccineLotController::class)->only(['update', 'destroy']);
    });

    Route::apiResource('/employees', Controllers\EmployeeController::class)->except('show');


});
