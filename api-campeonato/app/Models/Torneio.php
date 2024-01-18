<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneio extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function campeonatos()
    {
        return $this->hasMany(Campeonato::class);
    }

    public function fases()
    {
        return $this->hasMany(Fase::class);
    }
}
