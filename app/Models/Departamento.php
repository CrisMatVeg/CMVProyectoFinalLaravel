<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'project_id',
        'name',
        'description',
    ];

    // Un departamento pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'project_id');
    }

    // Relación con los usuarios que pertenecen a este departamento
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_departamento', 'department_id', 'user_id')
                    ->withTimestamps();
    }

    // Relación con las tareas de este departamento
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'department_id');
    }
}
