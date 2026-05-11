<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $table = 'archivos';

    protected $fillable = [
        'proyecto_id',
        'uploaded_by',
        'name',
        'description',
        'original_name',
        'path',
        'mime_type',
        'size',
        'categoria',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function uploader()
    {
        return $this->belongsTo(Usuario::class, 'uploaded_by');
    }

    public function tipos()
    {
        return $this->belongsToMany(Tipo::class, 'archivo_tipo');
    }

    public function getSizeFormateadoAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024,    1) . ' KB';
        return $bytes . ' B';
    }
}
