<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProyectoAcceso;
use App\Models\Usuario;

/**
 * Modelo que representa un proyecto de la aplicación.
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property int    $created_by
 */
class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    /**
     * Usuario propietario (creador) del proyecto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    /**
     * Devuelve un Builder con todos los miembros del proyecto:
     * usuarios con acceso registrado en proyecto_accesos más el owner.
     * No es una relación Eloquent estándar — hace 2 queries internas.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function miembros()
    {
        $ids = ProyectoAcceso::where('proyecto_id', $this->id)
            ->pluck('user_id')
            ->push($this->created_by)
            ->unique()
            ->values();

        return Usuario::whereIn('id', $ids);
    }

    /**
     * Tareas pertenecientes al proyecto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'project_id');
    }

    /**
     * Accesos de miembros al proyecto (un registro por usuario+tipo).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accesos()
    {
        return $this->hasMany(ProyectoAcceso::class);
    }

    /**
     * Archivos adjuntos al proyecto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }

    /**
     * Hilos del foro del proyecto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function foroHilos()
    {
        return $this->hasMany(ForoHilo::class);
    }

    /**
     * Usuarios que participan en el proyecto a través de sus tareas.
     * Nota: hasManyThrough no resuelve la tabla pivote participaciones,
     * usar miembros() para obtener usuarios reales del proyecto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function usuarios()
    {
        return $this->hasManyThrough(
            Usuario::class,
            Tarea::class,
            'project_id',
            'id',
            'id',
            'id'
        );
    }

    /**
     * Proyectos en los que el usuario participa como miembro invitado
     * (acceso registrado en proyecto_accesos).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proyectosParticipando()
    {
        return $this->belongsToMany(Usuario::class, 'proyecto_accesos', 'proyecto_id', 'user_id');
    }
}
