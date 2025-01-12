<?php

namespace App\Dtos\Employee;

use App\Dtos\BaseDto;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Validator;

readonly class UpdateEmployeeVaccineDto extends BaseDto
{

    public function __construct(
        public int             $vaccine_id,
        public int             $vaccine_lot_id,
        public int             $dose_number,
        public CarbonImmutable $applied_at,
        public string          $lot_id,
        public CarbonImmutable $expiration_date,
    )
    {
    }

    public function validateFromArray(array $data): static
    {
        $validated = Validator::validate($data, [
            'vaccine_id' => ['required', 'int', 'exists:vaccines,id'],
            'dose_number' => ['required', 'int', 'min:1', 'max:3'],
            'applied_at' => ['required', 'date', 'before_or_equal:today'],
            'lot_id' => ['required', 'string', 'min:1', 'max:255'],
            'expiration_date' => ['required', 'date'],
        ]);

        return new static(
            vaccine_id: $validated['vaccine_id'],
            vaccine_lot_id: $validated['vaccine_lot_id'],
            dose_number: $validated['dose_number'],
            applied_at: $validated['applied_at'],

            lot_id: $validated['lot_id'],
            expiration_date: $validated['expiration_date'],
        );
    }
}
