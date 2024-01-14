<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    protected $fillable = ['fase_id', 'equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b'];

    public function fase()
    {
        return $this->belongsTo(Fase::class);
    }

    public function equipeA()
    {
        return $this->belongsTo(Time::class, 'equipe_a_id');
    }

    public function equipeB()
    {
        return $this->belongsTo(Time::class, 'equipe_b_id');
    }
}
