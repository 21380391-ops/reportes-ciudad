<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'zone_id',
        'titulo',
        'descripcion',
        'referencia_direccion',
        'latitud',
        'longitud',
        'estado',
        'notas_moderador',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class, 'report_id');
    }

    public function fotoInicial()
    {
        return $this->hasOne(ReportImage::class, 'report_id')->where('tipo', 'inicial');
    }

    public function fotoSolucion()
    {
        return $this->hasOne(ReportImage::class, 'report_id')->where('tipo', 'solucion');
    }
}
