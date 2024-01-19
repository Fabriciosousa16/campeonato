<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $table = 'penaltys';


    protected $fillable = [
        'resultado_id',
        'gols_equipe_a',
        'gols_equipe_b',
    ];

    public function resultado()
    {
        return $this->belongsTo(Resultado::class);
    }
    
}
