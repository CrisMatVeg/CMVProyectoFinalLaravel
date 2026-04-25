<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoAcceso extends Model
{
    protected $table = 'proyecto_accesos';

    protected $fillable = ['proyecto_id', 'user_id', 'tipo_id'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }
}
