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

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'project_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(Estado::class, 'status_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'participaciones', 'task_id', 'user_id')
                    ->withPivot(['proposed_at', 'accepted_at', 'actual_hours'])
                    ->withTimestamps();
    }

    // Tareas de las que esta depende (requisitos previos)
    public function dependencias()
    {
        return $this->belongsToMany(
            Tarea::class,
            'tarea_dependencias',
            'task_id',
            'depends_on_id'
        )->with('status');
    }

    // Tareas que dependen de esta
    public function dependientes()
    {
        return $this->belongsToMany(
            Tarea::class,
            'tarea_dependencias',
            'depends_on_id',
            'task_id'
        );
    }

    // True si alguna tarea requerida no está Terminada
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
