<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForoHilo extends Model
{
    protected $table = 'foro_hilos';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'titulo',
        'contenido',
        'fijado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function mensajes()
    {
        return $this->hasMany(ForoMensaje::class, 'hilo_id')->orderBy('created_at', 'asc');
    }

    public function ultimoMensaje()
    {
        return $this->hasOne(ForoMensaje::class, 'hilo_id')->latestOfMany();
    }
}
