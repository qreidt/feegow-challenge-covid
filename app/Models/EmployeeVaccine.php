<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeVaccine extends Model
{
    use HasFactory;

    protected $table = 'employee_vaccines';

    protected $fillable = [
        'employee_id',
        'vaccine_id',
        'vaccine_lot_id',
        'dose_number',
        'applied_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'applied_at' => 'immutable_date',
        'dose_number' => 'integer'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function vaccineLot(): BelongsTo
    {
        return $this->belongsTo(VaccineLot::class);
    }
}
