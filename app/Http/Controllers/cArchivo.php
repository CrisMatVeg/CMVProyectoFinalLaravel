<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Proyecto;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class cArchivo extends Controller
{
    private const CATEGORIAS = [
        'texto'  => ['txt', 'doc', 'docx', 'csv', 'rtf', 'odt'],
        'pdf'    => ['pdf'],
        'audio'  => ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac'],
        'imagen' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
        'video'  => ['mp4', 'mov', 'avi', 'webm', 'mkv'],
    ];

    public function index($proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $tipos    = Tipo::all();

        $archivos = Archivo::where('proyecto_id', $proyectoId)
            ->with(['uploader', 'tipos'])
            ->latest()
            ->get();

        return view('archivos', compact('proyecto', 'tipos', 'archivos'));
    }

    public function store(Request $request, $proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);

        $request->validate([
            'archivo'      => 'required|file|max:51200',
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

        $archivo = Archivo::create([
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
            $archivo->tipos()->sync($request->tipo_ids);
        }

        return redirect()->route('proyecto.archivos', $proyectoId)
            ->with('success', 'Archivo subido correctamente.');
    }

    public function destroy($id)
    {
        $archivo  = Archivo::findOrFail($id);
        $proyecto = $archivo->proyecto_id;
        $usuario  = Auth::user();

        $esOwner    = $archivo->proyecto->created_by === $usuario->id;
        $esUploader = $archivo->uploaded_by === $usuario->id;

        if (!$esOwner && !$esUploader) {
            return redirect()->route('proyecto.archivos', $proyecto)
                ->with('error', 'No tienes permiso para eliminar este archivo.');
        }

        Storage::disk('public')->delete($archivo->path);
        $archivo->delete();

        return redirect()->route('proyecto.archivos', $proyecto)
            ->with('success', 'Archivo eliminado.');
    }

    public function download($id)
    {
        $archivo = Archivo::findOrFail($id);
        return Storage::disk('public')->download($archivo->path, $archivo->original_name);
    }

    public function preview($id)
    {
        $archivo = Archivo::findOrFail($id);

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

    private function resolverCategoria(string $extension): string
    {
        foreach (self::CATEGORIAS as $categoria => $extensiones) {
            if (in_array($extension, $extensiones)) {
                return $categoria;
            }
        }
        return 'texto';
    }
}
