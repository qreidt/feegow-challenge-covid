<?php

namespace App\Dtos\Vaccine;

use App\Dtos\BaseDto;

readonly class CreateVaccineDto extends BaseDto
{

    public function __construct(
        public string $name,
        public string $slug,
    )
    {
    }
}
