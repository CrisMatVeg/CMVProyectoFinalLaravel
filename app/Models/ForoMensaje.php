<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un mensaje dentro de un hilo del foro.
 * El primer mensaje de cada hilo (el raíz) almacena los adjuntos
 * del post original y tiene contenido null.
 *
 * @property int         $id
 * @property int         $hilo_id
 * @property int         $user_id
 * @property string|null $contenido
 */
class ForoMensaje extends Model
{
    protected $table = 'foro_mensajes';

    protected $fillable = [
        'hilo_id',
        'user_id',
        'contenido',
    ];

    /**
     * Hilo al que pertenece el mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hilo()
    {
        return $this->belongsTo(ForoHilo::class, 'hilo_id');
    }

    /**
     * Usuario que escribió el mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    /**
     * Archivos adjuntos del mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos()
    {
        return $this->hasMany(ForoArchivo::class, 'mensaje_id');
    }
}
