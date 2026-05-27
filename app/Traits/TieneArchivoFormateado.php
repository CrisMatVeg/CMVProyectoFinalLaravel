<?php

namespace App\Traits;

/**
 * Proporciona un accessor que devuelve el tamaño del archivo
 * en formato legible (B, KB, MB).
 * Requiere que el modelo tenga un atributo `size` con el tamaño en bytes.
 */
trait TieneArchivoFormateado
{
    /**
     * Devuelve el tamaño del archivo en formato humano (B, KB, MB).
     *
     * @return string
     */
    public function getSizeFormateadoAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
