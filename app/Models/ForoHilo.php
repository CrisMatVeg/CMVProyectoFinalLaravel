<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un hilo del foro de un proyecto.
 *
 * @property int         $id
 * @property int         $proyecto_id
 * @property int         $user_id
 * @property string      $titulo
 * @property string      $contenido
 */
class ForoHilo extends Model
{
    protected $table = 'foro_hilos';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'titulo',
        'contenido',
    ];

    /**
     * Proyecto al que pertenece el hilo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Usuario que creó el hilo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    /**
     * Mensajes del hilo ordenados de más antiguo a más reciente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mensajes()
    {
        return $this->hasMany(ForoMensaje::class, 'hilo_id')->orderBy('created_at', 'asc');
    }

    /**
     * Último mensaje publicado en el hilo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ultimoMensaje()
    {
        return $this->hasOne(ForoMensaje::class, 'hilo_id')->latestOfMany();
    }
}
