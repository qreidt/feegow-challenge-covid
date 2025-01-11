<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'birth_date' => 'immutable_date',
    ];
}
