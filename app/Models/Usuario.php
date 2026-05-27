<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Proyecto;

/**
 * Modelo principal de usuario de la aplicación.
 *
 * @property int    $id
 * @property string $username
 * @property string $description
 * @property string $email
 * @property string $password
 */
class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'username',
        'description',
        'email',
        'password',
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

    // ── Accessors ────────────────────────────────────────────────

    /**
     * Devuelve las iniciales del usuario extraídas del campo description.
     * Toma la primera letra de las dos primeras palabras.
     *
     * @return string Máximo 2 caracteres en mayúsculas.
     */
    public function getInitialsAttribute(): string
    {
        $partes = explode(' ', trim($this->description));
        $primera = strtoupper(substr($partes[0], 0, 1));
        $segunda = isset($partes[1]) ? strtoupper(substr($partes[1], 0, 1)) : '';

        return $primera . $segunda;
    }

    // ── Relaciones ───────────────────────────────────────────────

    /**
     * Proyectos creados por este usuario.
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }

    /**
     * Proyectos en los que el usuario participa como miembro invitado
     * (excluyendo los que él mismo creó).
     * Nota: no es una relación Eloquent estándar; no soporta eager loading directo.
     */
    public function proyectosParticipando()
    {
        $ids = \App\Models\ProyectoAcceso::where('user_id', $this->id)
            ->pluck('proyecto_id')
            ->unique();

        return Proyecto::whereIn('id', $ids);
    }

    /**
     * Tareas asignadas a este usuario a través de la tabla participaciones.
     */
    public function tareas()
    {
        return $this->belongsToMany(Tarea::class, 'participaciones', 'user_id', 'task_id')
                    ->withTimestamps();
    }

    // ── Métodos de negocio ───────────────────────────────────────

    /**
     * Retorna los IDs de tipos (áreas) a los que tiene acceso este usuario en un proyecto.
     *
     * @param int $proyectoId
     * @return int[]
     */
    public function tiposAccesiblesEn(int $proyectoId): array
    {
        return ProyectoAcceso::where('proyecto_id', $proyectoId)
            ->where('user_id', $this->id)
            ->pluck('tipo_id')
            ->toArray();
    }
}
