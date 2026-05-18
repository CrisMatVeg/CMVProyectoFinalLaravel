<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForoMensaje extends Model
{
    protected $table = 'foro_mensajes';

    protected $fillable = [
        'hilo_id',
        'user_id',
        'contenido',
    ];

    public function hilo()
    {
        return $this->belongsTo(ForoHilo::class, 'hilo_id');
    }

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function archivos()
    {
        return $this->hasMany(ForoArchivo::class, 'mensaje_id');
    }
}
