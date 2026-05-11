<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['proyecto_id', 'game_id', 'nombre', 'descripcion', 'precio', 'tipo'];

    public function proyecto() { return $this->belongsTo(\App\Models\Proyecto::class); }

    protected $casts = [
        'precio' => 'integer',
    ];
}
