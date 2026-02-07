<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    // Relación: un proyecto pertenece a un usuario
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }
}
