<?php

namespace App\Traits;

/**
 * Proporciona categorización de archivos por extensión.
 * Usado en controladores que gestionan subida de archivos.
 */
trait CategorizaArchivos
{
    /**
     * Mapa de extensiones agrupadas por categoría.
     */
    private static array $CATEGORIAS_ARCHIVO = [
        'texto'  => ['txt', 'doc', 'docx', 'csv', 'rtf', 'odt'],
        'pdf'    => ['pdf'],
        'audio'  => ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac'],
        'imagen' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
        'video'  => ['mp4', 'mov', 'avi', 'webm', 'mkv'],
    ];

    /**
     * Devuelve la categoría correspondiente a una extensión de archivo.
     * Si la extensión no está reconocida, retorna 'texto' como valor por defecto.
     *
     * @param string $extension Extensión sin punto, en minúsculas.
     * @return string Nombre de la categoría.
     */
    private function resolverCategoria(string $extension): string
    {
        foreach (self::$CATEGORIAS_ARCHIVO as $categoria => $extensiones) {
            if (in_array($extension, $extensiones)) {
                return $categoria;
            }
        }

        return 'texto';
    }
}
