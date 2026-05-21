<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/* use Illuminate\Database\Eloquent\Model; */
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Proyecto;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $fillable = [
        'username',
        'description',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }

    public function proyectosParticipando()
    {
        $ids = \App\Models\ProyectoAcceso::where('user_id', $this->id)
            ->pluck('proyecto_id')
            ->unique();

        return Proyecto::whereIn('id', $ids);
    }

    public function tareas()
    {
        return $this->belongsToMany(Tarea::class, 'participaciones', 'user_id', 'task_id')
                    ->withTimestamps();
    }

    public function tiposAccesiblesEn(int $proyectoId): array
    {
        return ProyectoAcceso::where('proyecto_id', $proyectoId)
            ->where('user_id', $this->id)
            ->pluck('tipo_id')
            ->toArray();
    }
}
