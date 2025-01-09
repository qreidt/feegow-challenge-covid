<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccineLot extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vaccine_id',
        'lot_id',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'immutable_date',
    ];

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }
}
