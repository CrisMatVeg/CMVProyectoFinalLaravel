<?php

namespace App\Http\Controllers;

use App\Traits\CategorizaArchivos;
use App\Models\ForoArchivo;
use App\Models\ForoHilo;
use App\Models\ForoMensaje;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Gestiona el foro de un proyecto: hilos, respuestas y archivos adjuntos.
 * Todas las operaciones requieren ser miembro del proyecto.
 */
class cForo extends Controller
{
    use CategorizaArchivos;

    /**
     * Lista todos los hilos del foro de un proyecto.
     *
     * @param int $proyectoId
     * @return \Illuminate\View\View
     */
    public function index($proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $hilos = ForoHilo::where('proyecto_id', $proyectoId)
            ->with(['autor', 'ultimoMensaje.autor'])
            ->withCount('mensajes')
            ->orderByDesc('updated_at')
            ->get();

        return view('foro', compact('proyecto', 'hilos'));
    }

    /**
     * Muestra un hilo con todas sus respuestas.
     * Separa el mensaje raíz (adjuntos del hilo original) de las respuestas.
     *
     * @param int $proyectoId
     * @param int $hiloId
     * @return \Illuminate\View\View
     */
    public function show($proyectoId, $hiloId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $this->verificarMiembro($proyecto);

        $hilo = ForoHilo::where('proyecto_id', $proyectoId)->findOrFail($hiloId);

        // Mensaje raíz: almacena adjuntos del post original (sin contenido textual)
        $mensajeRaiz  = ForoMensaje::where('hilo_id', $hilo->id)
            ->whereNull('contenido')
            ->oldest()
            ->with('archivos')
            ->first();

        $hiloAdjuntos = $mensajeRaiz ? $mensajeRaiz->archivos : collect();

        // Respuestas visibles (excluye el mensaje raíz)
        $mensajes = ForoMensaje::where('hilo_id', $hilo->id)
            ->when($mensajeRaiz, fn($q) => $q->where('id', '!=', $mensajeRaiz->id))
            ->with(['autor', 'archivos'])
            ->oldest()
            ->get();

        return view('hilo', compact('proyecto', 'hilo', 'mensajes', 'hiloAdjuntos'));
    }

    /**
     * Crea un nuevo hilo en el foro.
     * Si se adjuntan archivos, crea además un mensaje raíz para almacenarlos.
     * Hilo + mensaje raíz se crean en una transacción para garantizar coherencia.
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
            'titulo'      => 'required|string|max:200',
            'contenido'   => 'required|string|max:10000',
            'archivos'    => 'nullable|array',
            'archivos.*'  => 'file|max:51200',
        ]);

        $hilo = DB::transaction(function () use ($proyectoId, $request) {
            $h = ForoHilo::create([
                'proyecto_id' => $proyectoId,
                'user_id'     => Auth::id(),
                'titulo'      => $request->titulo,
                'contenido'   => $request->contenido,
            ]);

            if ($request->hasFile('archivos')) {
                $mensajeRaiz = ForoMensaje::create([
                    'hilo_id'   => $h->id,
                    'user_id'   => Auth::id(),
                    'contenido' => null,
                ]);
                $this->procesarAdjuntos($request->file('archivos'), $mensajeRaiz);
            }

            return $h;
        });

        return redirect()->route('proyecto.foro.show', [$proyectoId, $hilo->id])
            ->with('success', 'Hilo creado correctamente.');
    }

    /**
     * Añade una respuesta a un hilo existente.
     * La respuesta debe tener texto o al menos un archivo adjunto.
     *
     * @param Request $request
     * @param int     $proyectoId
     * @param int     $hiloId
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Elimina un hilo y todos sus mensajes y archivos físicos asociados.
     * Solo pueden eliminarlo el autor del hilo o el owner del proyecto.
     *
     * @param int $proyectoId
     * @param int $hiloId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($proyectoId, $hiloId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $hilo     = ForoHilo::where('proyecto_id', $proyectoId)->findOrFail($hiloId);

        if ($hilo->user_id !== Auth::id() && $proyecto->created_by !== Auth::id()) {
            return redirect()->route('proyecto.foro', $proyectoId)
                ->with('error', 'No tienes permiso para eliminar este hilo.');
        }

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

    /**
     * Elimina una respuesta individual y sus archivos físicos.
     * Solo pueden eliminarla el autor o el owner del proyecto.
     *
     * @param int $proyectoId
     * @param int $hiloId
     * @param int $mensajeId
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Descarga un archivo adjunto del foro.
     * Requiere ser miembro del proyecto al que pertenece el hilo.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadArchivo($id)
    {
        $adj = ForoArchivo::with('mensaje.hilo')->findOrFail($id);
        $this->verificarMiembro($adj->mensaje->hilo->proyecto);

        return response()->download(
            Storage::disk('public')->path($adj->path),
            $adj->original_name
        );
    }

    /**
     * Devuelve en JSON los primeros 5000 caracteres de un adjunto de texto del foro.
     * Requiere ser miembro del proyecto.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function previewArchivo($id)
    {
        $adj = ForoArchivo::with('mensaje.hilo')->findOrFail($id);
        $this->verificarMiembro($adj->mensaje->hilo->proyecto);

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

    /**
     * Procesa y persiste una lista de archivos adjuntos vinculándolos a un mensaje.
     *
     * @param \Illuminate\Http\UploadedFile[] $files
     * @param ForoMensaje                     $mensaje
     */
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
}
