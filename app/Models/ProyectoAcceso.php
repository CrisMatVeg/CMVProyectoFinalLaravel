<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el acceso de un usuario a un área (tipo) de un proyecto.
 * Un usuario puede tener múltiples registros si tiene acceso a varias áreas.
 *
 * @property int $id
 * @property int $proyecto_id
 * @property int $user_id
 * @property int $tipo_id
 */
class ProyectoAcceso extends Model
{
    protected $table = 'proyecto_accesos';

    protected $fillable = ['proyecto_id', 'user_id', 'tipo_id'];

    /**
     * Proyecto al que pertenece este acceso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Usuario que tiene este acceso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    /**
     * Tipo (área) a la que da acceso este registro.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }
}
