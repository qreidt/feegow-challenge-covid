<?php

namespace App\Models;

use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'file_path',
        'ready_at',
    ];

    protected $casts = [
        'type' => ReportType::class,
        'ready_at' => 'immutable_datetime',
    ];

    public function fileUrl(): Attribute
    {
        return Attribute::get(fn() => URL::temporarySignedRoute('reports.show', now()->addDay(), [
            'report' => $this
        ]));
    }
}
