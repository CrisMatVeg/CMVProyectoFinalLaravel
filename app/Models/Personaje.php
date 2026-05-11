<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    use HasFactory;

    protected $fillable = ['proyecto_id', 'game_id', 'nombre', 'vida', 'ataque', 'defensa', 'velocidad'];

    public function proyecto() { return $this->belongsTo(\App\Models\Proyecto::class); }

    protected $casts = [
        'vida'      => 'integer',
        'ataque'    => 'integer',
        'defensa'   => 'integer',
        'velocidad' => 'float',
    ];
}
