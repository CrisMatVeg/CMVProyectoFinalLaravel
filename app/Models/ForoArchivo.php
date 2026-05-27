<?php

namespace App\Models;

use App\Traits\TieneArchivoFormateado;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un archivo adjunto a un mensaje del foro.
 *
 * @property int    $id
 * @property int    $mensaje_id
 * @property int    $user_id
 * @property string $original_name
 * @property string $path
 * @property string $mime_type
 * @property int    $size
 * @property string $categoria
 */
class ForoArchivo extends Model
{
    use TieneArchivoFormateado;

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

    /**
     * Mensaje al que pertenece el archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mensaje()
    {
        return $this->belongsTo(ForoMensaje::class, 'mensaje_id');
    }

    /**
     * Usuario que subió el archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
