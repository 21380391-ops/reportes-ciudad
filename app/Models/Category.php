<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nombre', 'icono'];

    // Una categoría puede estar en muchos reportes
    public function reportes()
    {
        return $this->hasMany(Report::class);
    }
}
