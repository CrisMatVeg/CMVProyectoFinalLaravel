<?php

namespace App\Events;

use App\Models\ForoMensaje;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensajeEnviado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $hiloId;
    public int $mensajeId;

    public function __construct(ForoMensaje $mensaje)
    {
        $this->hiloId    = $mensaje->hilo_id;
        $this->mensajeId = $mensaje->id;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->hiloId)];
    }

    // Nombre corto para el listener de Echo en Livewire
    public function broadcastAs(): string
    {
        return 'MensajeEnviado';
    }

    // Solo IDs — el componente recarga desde BD para evitar serializar relaciones
    public function broadcastWith(): array
    {
        return [
            'hilo_id'    => $this->hiloId,
            'mensaje_id' => $this->mensajeId,
        ];
    }
}
