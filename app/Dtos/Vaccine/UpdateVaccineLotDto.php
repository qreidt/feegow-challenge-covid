<?php

namespace App\Dtos\Vaccine;

use App\Dtos\BaseDto;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Validator;

readonly class UpdateVaccineLotDto extends BaseDto
{

    public function __construct(
        public string          $lot_id,
        public CarbonImmutable $expiration_date
    )
    {
    }

    public static function validateFromArray(array $data): static
    {
        $validated = Validator::validate($data, [
            'lot_id' => ['required', 'string', 'min:1', 'max:255'],
            'expiration_date' => ['required', 'date'],
        ]);

        return new static(
            lot_id: $validated['lot_id'],
            expiration_date: $validated['expiration_date'],
        );
    }
}
