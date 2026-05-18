<?php

use App\Models\ForoHilo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{hiloId}', function ($user, $hiloId) {
    $hilo = ForoHilo::find($hiloId);
    if (!$hilo) return false;

    $proyecto = Proyecto::find($hilo->proyecto_id);
    if (!$proyecto) return false;

    return $proyecto->created_by === $user->id
        || $proyecto->miembros()->where('user_id', $user->id)->exists();
});
