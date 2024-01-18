<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;

    protected $fillable = ['name','torneio_id'];

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }

    public function torneio()
    {
        return $this->belongsTo(Torneio::class);
    }
}
