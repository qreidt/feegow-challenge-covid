<?php

namespace App\Dtos\Employee;

use App\Dtos\BaseDto;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Validator;

readonly class UpdateEmployeeDto extends BaseDto
{
    public function __construct(
        public string          $name,
        public CarbonImmutable $birth_date,
        public bool            $has_comorbidity,
    )
    {
    }

    public static function validateFromArray(array $data): static
    {
        $validated = Validator::validate($data, [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'birth_date' => ['required', 'string', 'date', 'before:today'],
            'has_comorbidity' => ['required', 'boolean'],
        ]);

        return new static(
            name: $validated['name'],
            birth_date: CarbonImmutable::make($validated['birth_date']),
            has_comorbidity: $validated['has_comorbidity'],
        );
    }
}
