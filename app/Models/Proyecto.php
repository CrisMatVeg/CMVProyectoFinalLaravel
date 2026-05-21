<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProyectoAcceso;
use App\Models\Usuario;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    // Relación: un proyecto pertenece a un usuario (Owner)
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function miembros()
    {
        $ids = ProyectoAcceso::where('proyecto_id', $this->id)
            ->pluck('user_id')
            ->push($this->created_by)
            ->unique()
            ->values();

        return Usuario::whereIn('id', $ids);
    }

    // Relación: un proyecto tiene muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'project_id');
    }

    public function accesos()
    {
        return $this->hasMany(ProyectoAcceso::class);
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }

    public function foroHilos()
    {
        return $this->hasMany(ForoHilo::class);
    }

    // Relación: usuarios involucrados en el proyecto (a través de sus tareas)
    public function usuarios()
    {
        return $this->hasManyThrough(
            Usuario::class,
            Tarea::class,
            'project_id', // FK en tareas
            'id',         // FK en usuarios (vía participaciones, esto es más complejo para hasManyThrough directo)
            'id',         // local key proyecto
            'id'          // local key tarea
        );
    }
}
