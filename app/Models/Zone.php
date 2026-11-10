<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['nombre'];

    // Una zona puede estar en muchos reportes
    public function reportes()
    {
        return $this->hasMany(Report::class);
    }
}
