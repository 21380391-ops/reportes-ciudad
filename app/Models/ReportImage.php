<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    protected $fillable = [
        'report_id',
        'ruta_imagen',
        'tipo',
    ];

    public function reporte()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
