<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Modelo que representa una tarea dentro de un proyecto.
 *
 * @property int         $id
 * @property string      $title
 * @property string|null $description
 * @property int         $project_id
 * @property int|null    $type_id
 * @property int|null    $status_id
 * @property float|null  $estimated_hours
 * @property string|null $start_date
 * @property string|null $end_date
 * @property bool        $is_milestone
 */
class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'type_id',
        'status_id',
        'estimated_hours',
        'start_date',
        'end_date',
        'is_milestone',
    ];

    protected $casts = [
        'is_milestone' => 'boolean',
    ];

    /**
     * Proyecto al que pertenece la tarea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'project_id');
    }

    /**
     * Tipo (área) al que pertenece la tarea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'type_id');
    }

    /**
     * Estado actual de la tarea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Estado::class, 'status_id');
    }

    /**
     * Usuarios asignados a la tarea a través de la tabla participaciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'participaciones', 'task_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Notas internas de la tarea, ordenadas de más reciente a más antigua.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notas()
    {
        return $this->hasMany(NotaTarea::class, 'task_id')->with('usuario')->latest();
    }

    /**
     * Indica si la tarea ha superado su fecha de fin sin estar terminada.
     *
     * @return bool
     */
    public function estaRetrasada(): bool
    {
        if (!$this->end_date) return false;
        if ($this->status && $this->status->name === 'Terminada') return false;
        return Carbon::parse($this->end_date)->startOfDay()->isPast();
    }

    /**
     * Tareas de las que esta depende (deben completarse primero).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dependencias()
    {
        return $this->belongsToMany(
            Tarea::class,
            'tarea_dependencias',
            'task_id',
            'depends_on_id'
        )->with('status');
    }

    /**
     * Tareas que dependen de esta para poder avanzar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dependientes()
    {
        return $this->belongsToMany(
            Tarea::class,
            'tarea_dependencias',
            'depends_on_id',
            'task_id'
        );
    }

    /**
     * Indica si alguna tarea requerida aún no está terminada.
     * Requiere que la relación dependencias esté cargada.
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        if (!$this->relationLoaded('dependencias') || $this->dependencias->isEmpty()) {
            return false;
        }
        return $this->dependencias->contains(
            fn($d) => !$d->status || $d->status->name !== 'Terminada'
        );
    }
}
