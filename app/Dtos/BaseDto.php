<?php

namespace App\Dtos;

readonly abstract class BaseDto
{

    public function toArray(): array
    {
        return (array) $this;
    }
}
