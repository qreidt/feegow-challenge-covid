<?php

namespace App\Dtos\Employee;

use App\Dtos\BaseDto;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Validator;

readonly class CreateEmployeeDto extends BaseDto
{
    public function __construct(
        public string          $name,
        public string          $cpf,
        public CarbonImmutable $birth_date,
        public bool            $has_comorbidity,
    )
    {
    }

    public static function validateFromArray(array $data): static
    {
        $validated = Validator::validate($data, [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'cpf' => [
                'required', 'string', 'size:14',
                'regex:/^[A-Z0-9]{3}\.[A-Z0-9]{3}\.[A-Z0-9]{3}-[A-Z0-9]{2}$/',
                'unique:employees,cpf'
            ],
            'birth_date' => ['required', 'string', 'date', 'before:today'],
            'has_comorbidity' => ['required', 'boolean'],
        ]);

        return new static(
            name: $validated['name'],
            cpf: $validated['cpf'],
            birth_date: CarbonImmutable::make($validated['birth_date']),
            has_comorbidity: $validated['has_comorbidity'],
        );
    }
}
