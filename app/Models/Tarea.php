<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'title',
        'description',
        'estado',
        'department_id',
    ];

    // Relación con el departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'department_id');
    }

    // Relación con los usuarios asignados a la tarea
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'tarea_usuario', 'task_id', 'user_id')
                    ->withTimestamps();
    }
}
