<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'cpf',
        'name',
        'birth_date',
        'has_comorbidity',
    ];

    protected $casts = [
        'birth_date' => 'immutable_date:Y-m-d',
        'has_comorbidity' => 'boolean',
    ];

    public function employeeVaccines(): HasMany
    {
        return $this->hasMany(EmployeeVaccine::class);
    }

    public function anonimizeCpf(): void
    {
        $cpf = substr($this->cpf, 0, 3);
        $this->setAttribute('cpf', "$cpf.***.***-**");
    }
}
