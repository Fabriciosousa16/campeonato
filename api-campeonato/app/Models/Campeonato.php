<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'torneio_id', 'status_id'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function times()
    {
        return $this->hasMany(Time::class);
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }
}
