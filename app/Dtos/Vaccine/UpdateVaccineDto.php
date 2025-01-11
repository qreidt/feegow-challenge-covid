<?php

namespace App\Dtos\Vaccine;

use App\Dtos\BaseDto;
use Illuminate\Support\Facades\Validator;

readonly class UpdateVaccineDto extends BaseDto
{

    public function __construct(
        public string $name,
        public string $slug,
    )
    {
    }

    public static function validateFromArray(array $data): static
    {
        $validated = Validator::validate($data, [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'slug' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        return new static(
            name: $validated['name'],
            slug: $validated['slug'],
        );
    }
}
