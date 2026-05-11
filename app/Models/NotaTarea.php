<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaTarea extends Model
{
    use HasFactory;

    protected $table = 'notas_tarea';

    protected $fillable = [
        'task_id',
        'user_id',
        'contenido',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'task_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
