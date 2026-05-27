<?php

namespace App\Livewire;

use App\Events\MensajeEnviado;
use App\Models\ForoArchivo;
use App\Models\ForoHilo;
use App\Models\ForoMensaje;
use App\Models\Proyecto;
use App\Traits\CategorizaArchivos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Componente Livewire que gestiona el chat en tiempo real de un hilo del foro.
 * Escucha el canal privado de Broadcasting para actualizarse sin recargar la página.
 */
class ChatHilo extends Component
{
    use WithFileUploads, CategorizaArchivos;

    /** @var int ID del proyecto al que pertenece el hilo */
    public int $proyectoId;

    /** @var int ID del hilo de foro */
    public int $hiloId;

    /** @var int ID del owner del proyecto (para mostrar controles de moderación) */
    public int $proyectoOwnerId;

    /** @var string Texto que el usuario está redactando */
    public string $contenido = '';

    /** @var array Archivos adjuntos pendientes de subida */
    public array $archivos = [];

    /**
     * Escucha eventos de Broadcasting en el canal privado del hilo.
     * Livewire 3 interpola {hiloId} con $this->hiloId al suscribirse al canal.
     * El re-render automático actualiza la lista de mensajes desde BD.
     */
    #[On('echo-private:chat.{hiloId},.MensajeEnviado')]
    public function refrescarMensajes(): void
    {
        // Re-render automático de Livewire recarga los mensajes
    }

    /**
     * Valida y persiste una nueva respuesta en el hilo.
     * Verifica que el usuario sea miembro del proyecto antes de guardar.
     * Despacha el evento MensajeEnviado para notificar a los demás usuarios en tiempo real.
     */
    public function guardarRespuesta(): void
    {
        $this->validate([
            'contenido'  => 'nullable|string|max:10000',
            'archivos'   => 'nullable|array',
            'archivos.*' => 'file|max:51200',
        ]);

        $contenidoTrimmed = trim($this->contenido);

        if (empty($contenidoTrimmed) && empty($this->archivos)) {
            $this->addError('contenido', 'La respuesta debe tener texto o al menos un archivo adjunto.');
            return;
        }

        $hilo     = ForoHilo::where('proyecto_id', $this->proyectoId)->findOrFail($this->hiloId);
        $proyecto = Proyecto::findOrFail($this->proyectoId);
        $userId   = Auth::id();

        if (!$proyecto->miembros()->where('id', $userId)->exists()) {
            abort(403);
        }

        $mensaje = ForoMensaje::create([
            'hilo_id'   => $hilo->id,
            'user_id'   => $userId,
            'contenido' => $contenidoTrimmed ?: null,
        ]);

        foreach ($this->archivos as $archivo) {
            $extension = strtolower($archivo->getClientOriginalExtension());
            $path      = $archivo->store("foro/{$hilo->id}/{$mensaje->id}", 'public');

            ForoArchivo::create([
                'mensaje_id'    => $mensaje->id,
                'user_id'       => $userId,
                'original_name' => $archivo->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $archivo->getMimeType(),
                'size'          => $archivo->getSize(),
                'categoria'     => $this->resolverCategoria($extension),
            ]);
        }

        $hilo->touch();

        // ShouldBroadcast → va a la cola database, no bloquea la respuesta HTTP
        MensajeEnviado::dispatch($mensaje);

        $this->contenido = '';
        $this->reset('archivos');
    }

    /**
     * Elimina una respuesta y sus archivos físicos asociados.
     * Solo puede hacerlo el autor del mensaje o el owner del proyecto.
     *
     * @param int $mensajeId
     */
    public function eliminarRespuesta(int $mensajeId): void
    {
        $mensaje  = ForoMensaje::where('hilo_id', $this->hiloId)->with('archivos')->findOrFail($mensajeId);
        $proyecto = Proyecto::findOrFail($this->proyectoId);

        if ($mensaje->user_id !== Auth::id() && $proyecto->created_by !== Auth::id()) {
            $this->addError('general', 'Sin permiso para eliminar.');
            return;
        }

        foreach ($mensaje->archivos as $adj) {
            Storage::disk('public')->delete($adj->path);
        }

        $mensaje->delete();
    }

    /**
     * Renderiza el componente con los mensajes del hilo, paleta de colores
     * y mapa de iconos por categoría de archivo.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $palette  = ['#14b8a6', '#ff00ff', '#3b82f6', '#4ade80', '#a855f7', '#fb923c', '#f59e0b', '#ec4899'];
        $catIcono = [
            'texto'  => 'fa-file-lines',
            'pdf'    => 'fa-file-pdf',
            'audio'  => 'fa-file-audio',
            'imagen' => 'fa-file-image',
            'video'  => 'fa-file-video',
        ];

        // Replica la lógica de cForo@show: excluir el mensaje raíz (adjuntos del hilo original)
        $mensajeRaizId = ForoMensaje::where('hilo_id', $this->hiloId)
            ->whereNull('contenido')
            ->oldest()
            ->value('id');

        $mensajes = ForoMensaje::where('hilo_id', $this->hiloId)
            ->when($mensajeRaizId, fn($q) => $q->where('id', '!=', $mensajeRaizId))
            ->with(['autor', 'archivos'])
            ->oldest()
            ->get();

        return view('livewire.chat-hilo', [
            'mensajes'  => $mensajes,
            'palette'   => $palette,
            'catIcono'  => $catIcono,
            'meColor'   => $palette[Auth::id() % count($palette)],
            'meInicial' => strtoupper(substr(Auth::user()->username ?? '?', 0, 1)),
        ]);
    }
}
