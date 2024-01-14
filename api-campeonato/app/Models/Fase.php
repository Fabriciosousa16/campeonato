<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }
}
