<?php

namespace App\Http\Controllers;

use App\Models\ForoArchivo;
use App\Models\ForoHilo;
use App\Models\ForoMensaje;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class cForo extends Controller
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
        $this->verificarMiembro($proyecto);

        $hilos = ForoHilo::where('proyecto_id', $proyectoId)
            ->with(['autor', 'ultimoMensaje.autor'])
            ->withCount('mensajes')
            ->orderByDesc('fijado')
            ->orderByDesc('updated_at')
            ->get();

        return view('foro', compact('proyecto', 'hilos'));
    }

    public function show($proyectoId, $hiloId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $hilo = ForoHilo::where('proyecto_id', $proyectoId)
            ->findOrFail($hiloId);

        // Mensaje raíz (adjuntos del hilo original)
        $mensajeRaiz = ForoMensaje::where('hilo_id', $hilo->id)
            ->whereNull('contenido')
            ->oldest()
            ->with('archivos')
            ->first();

        $hiloAdjuntos = $mensajeRaiz ? $mensajeRaiz->archivos : collect();

        // Respuestas (excluye el mensaje raíz si existe)
        $mensajes = ForoMensaje::where('hilo_id', $hilo->id)
            ->when($mensajeRaiz, fn($q) => $q->where('id', '!=', $mensajeRaiz->id))
            ->with(['autor', 'archivos'])
            ->oldest()
            ->get();

        return view('hilo', compact('proyecto', 'hilo', 'mensajes', 'hiloAdjuntos'));
    }

    public function store(Request $request, $proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $request->validate([
            'titulo'      => 'required|string|max:200',
            'contenido'   => 'required|string|max:10000',
            'archivos'    => 'nullable|array',
            'archivos.*'  => 'file|max:51200',
        ]);

        $hilo = ForoHilo::create([
            'proyecto_id' => $proyectoId,
            'user_id'     => Auth::id(),
            'titulo'      => $request->titulo,
            'contenido'   => $request->contenido,
        ]);

        if ($request->hasFile('archivos')) {
            $mensajeRaiz = ForoMensaje::create([
                'hilo_id'   => $hilo->id,
                'user_id'   => Auth::id(),
                'contenido' => null,
            ]);
            $this->procesarAdjuntos($request->file('archivos'), $mensajeRaiz);
        }

        return redirect()->route('proyecto.foro.show', [$proyectoId, $hilo->id])
            ->with('success', 'Hilo creado correctamente.');
    }

    public function storeRespuesta(Request $request, $proyectoId, $hiloId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $hilo = ForoHilo::where('proyecto_id', $proyectoId)->findOrFail($hiloId);

        $request->validate([
            'contenido'   => 'nullable|string|max:10000',
            'archivos'    => 'nullable|array',
            'archivos.*'  => 'file|max:51200',
        ]);

        if (empty(trim($request->contenido ?? '')) && !$request->hasFile('archivos')) {
            return redirect()->route('proyecto.foro.show', [$proyectoId, $hiloId])
                ->with('error', 'La respuesta debe tener texto o al menos un archivo adjunto.');
        }

        $mensaje = ForoMensaje::create([
            'hilo_id'   => $hilo->id,
            'user_id'   => Auth::id(),
            'contenido' => $request->filled('contenido') ? $request->contenido : null,
        ]);

        if ($request->hasFile('archivos')) {
            $this->procesarAdjuntos($request->file('archivos'), $mensaje);
        }

        $hilo->touch();

        return redirect()->route('proyecto.foro.show', [$proyectoId, $hiloId])
            ->with('success', 'Respuesta añadida.')
            ->withFragment('msg-' . $mensaje->id);
    }

    public function destroy($proyectoId, $hiloId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $hilo = ForoHilo::where('proyecto_id', $proyectoId)->findOrFail($hiloId);

        if ($hilo->user_id !== Auth::id() && $proyecto->created_by !== Auth::id()) {
            return redirect()->route('proyecto.foro', $proyectoId)
                ->with('error', 'No tienes permiso para eliminar este hilo.');
        }

        // Eliminar archivos físicos de todos los mensajes del hilo
        $mensajes = ForoMensaje::where('hilo_id', $hilo->id)->with('archivos')->get();
        foreach ($mensajes as $mensaje) {
            foreach ($mensaje->archivos as $adj) {
                Storage::disk('public')->delete($adj->path);
            }
        }

        $hilo->delete();

        return redirect()->route('proyecto.foro', $proyectoId)
            ->with('success', 'Hilo eliminado.');
    }

    public function destroyRespuesta($proyectoId, $hiloId, $mensajeId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $mensaje  = ForoMensaje::where('hilo_id', $hiloId)->with('archivos')->findOrFail($mensajeId);

        if ($mensaje->user_id !== Auth::id() && $proyecto->created_by !== Auth::id()) {
            return redirect()->route('proyecto.foro.show', [$proyectoId, $hiloId])
                ->with('error', 'No tienes permiso para eliminar esta respuesta.');
        }

        foreach ($mensaje->archivos as $adj) {
            Storage::disk('public')->delete($adj->path);
        }

        $mensaje->delete();

        return redirect()->route('proyecto.foro.show', [$proyectoId, $hiloId])
            ->with('success', 'Respuesta eliminada.');
    }

    public function downloadArchivo($id)
    {
        $adj = ForoArchivo::findOrFail($id);
        return Storage::disk('public')->download($adj->path, $adj->original_name);
    }

    public function previewArchivo($id)
    {
        $adj = ForoArchivo::findOrFail($id);

        if ($adj->categoria !== 'texto') {
            abort(400, 'Solo archivos de texto admiten previsualización de contenido.');
        }

        $content   = Storage::disk('public')->get($adj->path);
        $preview   = mb_substr($content, 0, 5000);
        $truncated = mb_strlen($content) > 5000;

        return response()->json([
            'content'   => $preview,
            'truncated' => $truncated,
        ]);
    }

    private function verificarMiembro(Proyecto $proyecto): void
    {
        $userId = Auth::id();
        $esMiembro = $proyecto->miembros()->where('user_id', $userId)->exists()
                  || $proyecto->created_by === $userId;

        if (!$esMiembro) {
            abort(403);
        }
    }

    private function procesarAdjuntos(array $files, ForoMensaje $mensaje): void
    {
        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $categoria = $this->resolverCategoria($extension);
            $path      = $file->store("foro/{$mensaje->hilo_id}/{$mensaje->id}", 'public');

            ForoArchivo::create([
                'mensaje_id'    => $mensaje->id,
                'user_id'       => Auth::id(),
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'categoria'     => $categoria,
            ]);
        }
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
