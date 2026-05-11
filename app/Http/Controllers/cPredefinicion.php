<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Personaje;
use App\Models\Item;
use App\Models\Dialogo;

class cPredefinicion extends Controller
{
    // ── Vista principal ────────────────────────────────────────
    public function index(Proyecto $proyecto)
    {
        $personajes = Personaje::where('proyecto_id', $proyecto->id)->orderBy('game_id')->get();
        $items      = Item::where('proyecto_id', $proyecto->id)->orderBy('game_id')->get();
        $dialogos   = Dialogo::where('proyecto_id', $proyecto->id)->orderBy('id_conversacion')->orderBy('orden')->get();

        return view('predefinicion', compact('proyecto', 'personajes', 'items', 'dialogos'));
    }

    // ── PERSONAJES ─────────────────────────────────────────────
    public function storePersonaje(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'game_id'   => 'required|string|max:100|unique:personajes,game_id,NULL,id,proyecto_id,' . $proyecto->id,
            'nombre'    => 'required|string|max:255',
            'vida'      => 'required|integer|min:0',
            'ataque'    => 'required|integer|min:0',
            'defensa'   => 'required|integer|min:0',
            'velocidad' => 'required|numeric|min:0',
        ]);

        Personaje::create(array_merge($data, ['proyecto_id' => $proyecto->id]));
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Personaje creado.');
    }

    public function updatePersonaje(Request $request, Proyecto $proyecto, Personaje $personaje)
    {
        $data = $request->validate([
            'game_id'   => 'required|string|max:100|unique:personajes,game_id,' . $personaje->id . ',id,proyecto_id,' . $proyecto->id,
            'nombre'    => 'required|string|max:255',
            'vida'      => 'required|integer|min:0',
            'ataque'    => 'required|integer|min:0',
            'defensa'   => 'required|integer|min:0',
            'velocidad' => 'required|numeric|min:0',
        ]);

        $personaje->update($data);
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Personaje actualizado.');
    }

    public function destroyPersonaje(Proyecto $proyecto, Personaje $personaje)
    {
        $personaje->delete();
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Personaje eliminado.');
    }

    // ── ÍTEMS ──────────────────────────────────────────────────
    public function storeItem(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'game_id'     => 'required|string|max:100|unique:items,game_id,NULL,id,proyecto_id,' . $proyecto->id,
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|integer|min:0',
            'tipo'        => 'required|in:Arma,Consumible,Misión',
        ]);

        Item::create(array_merge($data, ['proyecto_id' => $proyecto->id]));
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Ítem creado.');
    }

    public function updateItem(Request $request, Proyecto $proyecto, Item $item)
    {
        $data = $request->validate([
            'game_id'     => 'required|string|max:100|unique:items,game_id,' . $item->id . ',id,proyecto_id,' . $proyecto->id,
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|integer|min:0',
            'tipo'        => 'required|in:Arma,Consumible,Misión',
        ]);

        $item->update($data);
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Ítem actualizado.');
    }

    public function destroyItem(Proyecto $proyecto, Item $item)
    {
        $item->delete();
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Ítem eliminado.');
    }

    // ── DIÁLOGOS ───────────────────────────────────────────────
    public function storeDialogo(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'id_conversacion' => 'required|string|max:100',
            'orden'           => 'required|integer|min:0',
            'personaje_id'    => 'required|string|max:100',
            'texto'           => 'required|string',
        ]);

        Dialogo::create(array_merge($data, ['proyecto_id' => $proyecto->id]));
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo creado.');
    }

    public function updateDialogo(Request $request, Proyecto $proyecto, Dialogo $dialogo)
    {
        $data = $request->validate([
            'id_conversacion' => 'required|string|max:100',
            'orden'           => 'required|integer|min:0',
            'personaje_id'    => 'required|string|max:100',
            'texto'           => 'required|string',
        ]);

        $dialogo->update($data);
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo actualizado.');
    }

    public function destroyDialogo(Proyecto $proyecto, Dialogo $dialogo)
    {
        $dialogo->delete();
        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo eliminado.');
    }
}
