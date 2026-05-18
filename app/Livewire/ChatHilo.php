<?php

namespace App\Livewire;

use App\Events\MensajeEnviado;
use App\Models\ForoArchivo;
use App\Models\ForoHilo;
use App\Models\ForoMensaje;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatHilo extends Component
{
    use WithFileUploads;

    public int    $proyectoId;
    public int    $hiloId;
    public int    $proyectoOwnerId;

    public string $contenido = '';
    public array  $archivos  = [];

    private const CATEGORIAS = [
        'texto'  => ['txt', 'doc', 'docx', 'csv', 'rtf', 'odt'],
        'pdf'    => ['pdf'],
        'audio'  => ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac'],
        'imagen' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
        'video'  => ['mp4', 'mov', 'avi', 'webm', 'mkv'],
    ];

    // Livewire 3 interpola {hiloId} con $this->hiloId al suscribirse al canal
    #[On('echo-private:chat.{hiloId},.MensajeEnviado')]
    public function refrescarMensajes(): void
    {
        // El re-render automático de Livewire actualiza la lista desde BD
    }

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

        $esMiembro = $proyecto->created_by === $userId
                  || $proyecto->miembros()->where('user_id', $userId)->exists();
        if (!$esMiembro) {
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

        // Replica la lógica de cForo@show: excluir el mensaje raíz del hilo
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

    private function resolverCategoria(string $ext): string
    {
        foreach (self::CATEGORIAS as $cat => $exts) {
            if (in_array($ext, $exts)) {
                return $cat;
            }
        }
        return 'texto';
    }
}
