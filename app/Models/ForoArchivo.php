<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForoArchivo extends Model
{
    protected $table = 'foro_archivos';

    protected $fillable = [
        'mensaje_id',
        'user_id',
        'original_name',
        'path',
        'mime_type',
        'size',
        'categoria',
    ];

    public function mensaje()
    {
        return $this->belongsTo(ForoMensaje::class, 'mensaje_id');
    }

    public function uploader()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function getSizeFormateadoAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024,    1) . ' KB';
        return $bytes . ' B';
    }
}
