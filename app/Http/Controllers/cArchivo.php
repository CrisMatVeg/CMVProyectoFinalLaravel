<?php

namespace App\Http\Controllers;

use App\Traits\CategorizaArchivos;
use App\Models\Archivo;
use App\Models\Proyecto;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Gestiona la subida, descarga, previsualización y eliminación
 * de archivos adjuntos a un proyecto.
 */
class cArchivo extends Controller
{
    use CategorizaArchivos;

    /**
     * Lista todos los archivos de un proyecto.
     * Requiere ser miembro del proyecto.
     *
     * @param int $proyectoId
     * @return \Illuminate\View\View
     */
    public function index($proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $tipos    = Tipo::all();
        $archivos = Archivo::where('proyecto_id', $proyectoId)
            ->with(['uploader', 'tipos'])
            ->latest()
            ->get();

        return view('archivos', compact('proyecto', 'tipos', 'archivos'));
    }

    /**
     * Sube un nuevo archivo al proyecto y lo registra en la base de datos.
     * La operación de base de datos está envuelta en una transacción para
     * evitar registros huérfanos si falla la creación de relaciones.
     * Requiere ser miembro del proyecto.
     *
     * @param Request $request
     * @param int     $proyectoId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $request->validate([
            'archivo'      => 'required|file|max:51200|mimes:txt,doc,docx,csv,rtf,odt,pdf,mp3,wav,ogg,m4a,flac,aac,jpg,jpeg,png,gif,webp,svg,bmp,mp4,mov,avi,webm,mkv',
            'descripcion'  => 'nullable|string|max:500',
            'tipo_ids'     => 'nullable|array',
            'tipo_ids.*'   => 'exists:tipos,id',
        ]);

        $file      = $request->file('archivo');
        $extension = strtolower($file->getClientOriginalExtension());
        $categoria = $this->resolverCategoria($extension);

        $nombre = $request->filled('nombre')
            ? trim($request->nombre)
            : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $path = $file->store("proyectos/{$proyectoId}", 'public');

        DB::transaction(function () use ($proyectoId, $nombre, $request, $file, $path, $categoria) {
            $arch = Archivo::create([
                'proyecto_id'   => $proyectoId,
                'uploaded_by'   => Auth::id(),
                'name'          => $nombre,
                'description'   => $request->descripcion,
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'categoria'     => $categoria,
            ]);

            if ($request->filled('tipo_ids')) {
                $arch->tipos()->sync($request->tipo_ids);
            }
        });

        return redirect()->route('proyecto.archivos', $proyectoId)
            ->with('success', 'Archivo subido correctamente.');
    }

    /**
     * Elimina un archivo del disco y de la base de datos.
     * Solo lo pueden eliminar el owner del proyecto o quien lo subió.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $archivo    = Archivo::findOrFail($id);
        $proyectoId = $archivo->proyecto_id;
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $esOwner    = $archivo->proyecto->created_by === $usuario->id;
        $esUploader = $archivo->uploaded_by === $usuario->id;

        if (!$esOwner && !$esUploader) {
            return redirect()->route('proyecto.archivos', $proyectoId)
                ->with('error', 'No tienes permiso para eliminar este archivo.');
        }

        Storage::disk('public')->delete($archivo->path);
        $archivo->delete();

        return redirect()->route('proyecto.archivos', $proyectoId)
            ->with('success', 'Archivo eliminado.');
    }

    /**
     * Descarga un archivo al navegador del usuario.
     * Requiere ser miembro del proyecto al que pertenece el archivo.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $archivo = Archivo::with('proyecto')->findOrFail($id);
        $this->verificarMiembro($archivo->proyecto);

        return response()->download(
            Storage::disk('public')->path($archivo->path),
            $archivo->original_name
        );
    }

    /**
     * Devuelve en JSON los primeros 5000 caracteres de un archivo de texto.
     * Solo disponible para archivos de categoría 'texto'.
     * Requiere ser miembro del proyecto.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview($id)
    {
        $archivo = Archivo::with('proyecto')->findOrFail($id);
        $this->verificarMiembro($archivo->proyecto);

        if ($archivo->categoria !== 'texto') {
            abort(400, 'Solo archivos de texto admiten previsualización de contenido.');
        }

        $content   = Storage::disk('public')->get($archivo->path);
        $preview   = mb_substr($content, 0, 5000);
        $truncated = mb_strlen($content) > 5000;

        return response()->json([
            'content'   => $preview,
            'truncated' => $truncated,
        ]);
    }
}
