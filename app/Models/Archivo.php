<?php

namespace App\Models;

use App\Traits\TieneArchivoFormateado;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un archivo adjunto a un proyecto.
 *
 * @property int         $id
 * @property int         $proyecto_id
 * @property int         $uploaded_by
 * @property string      $name
 * @property string|null $description
 * @property string      $original_name
 * @property string      $path
 * @property string      $mime_type
 * @property int         $size
 * @property string      $categoria
 */
class Archivo extends Model
{
    use TieneArchivoFormateado;

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

    /**
     * Proyecto al que pertenece el archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Usuario que subió el archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader()
    {
        return $this->belongsTo(Usuario::class, 'uploaded_by');
    }

    /**
     * Tipos (áreas) a los que está asociado el archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tipos()
    {
        return $this->belongsToMany(Tipo::class, 'archivo_tipo');
    }
}
