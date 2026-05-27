<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Modelo que representa un enlace de invitación a un proyecto.
 * Cada invitación tiene un código único, fecha de expiración
 * y puede restringir el acceso a áreas (tipos) específicas.
 *
 * @property int         $id
 * @property int         $proyecto_id
 * @property string      $codigo
 * @property int         $created_by
 * @property \Carbon\Carbon $expires_at
 * @property int         $uses_count
 * @property array|null  $areas
 */
class Invitacion extends Model
{
    protected $table = 'invitaciones';

    protected $fillable = [
        'proyecto_id', 'codigo', 'created_by', 'expires_at', 'uses_count', 'areas',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'areas'      => 'array',
    ];

    /**
     * Proyecto al que pertenece la invitación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    /**
     * Usuario que creó la invitación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    /**
     * Scope para filtrar solo invitaciones que no han expirado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVigente(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Genera un código de invitación único con formato PROY-XXXXXXXX.
     *
     * @return string
     */
    public static function generarCodigo(): string
    {
        do {
            $c = 'PROY-' . strtoupper(Str::random(8));
        } while (static::where('codigo', $c)->exists());

        return $c;
    }

    /**
     * Indica si la invitación sigue siendo válida (no ha expirado).
     *
     * @return bool
     */
    public function esValida(): bool
    {
        return !$this->expires_at->isPast();
    }
}
