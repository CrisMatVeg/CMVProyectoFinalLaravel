<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialogo extends Model
{
    use HasFactory;

    protected $fillable = ['proyecto_id', 'id_conversacion', 'orden', 'personaje_id', 'texto'];

    public function proyecto() { return $this->belongsTo(\App\Models\Proyecto::class); }

    protected $casts = [
        'orden' => 'integer',
    ];
}
