<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Personaje;
use App\Models\Item;
use App\Models\Dialogo;

/**
 * Gestiona los datos de predefinición de un videojuego ligados a un proyecto:
 * personajes, ítems y diálogos.
 * Todas las operaciones de escritura están restringidas al owner del proyecto.
 */
class cPredefinicion extends Controller
{
    // ── Vista principal ────────────────────────────────────────

    /**
     * Muestra la vista de predefinición con todos los datos del proyecto.
     *
     * @param Proyecto $proyecto
     * @return \Illuminate\View\View
     */
    public function index(Proyecto $proyecto)
    {
        $personajes = Personaje::where('proyecto_id', $proyecto->id)->orderBy('game_id')->get();
        $items      = Item::where('proyecto_id', $proyecto->id)->orderBy('game_id')->get();
        $dialogos   = Dialogo::where('proyecto_id', $proyecto->id)
            ->orderBy('id_conversacion')
            ->orderBy('orden')
            ->get();

        return view('predefinicion', compact('proyecto', 'personajes', 'items', 'dialogos'));
    }

    // ── PERSONAJES ─────────────────────────────────────────────

    /**
     * Crea un nuevo personaje en el proyecto.
     * El game_id debe ser único dentro del mismo proyecto.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePersonaje(Request $request, Proyecto $proyecto)
    {
        $this->verificarOwner($proyecto);

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

    /**
     * Actualiza los datos de un personaje existente.
     *
     * @param Request   $request
     * @param Proyecto  $proyecto
     * @param Personaje $personaje
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePersonaje(Request $request, Proyecto $proyecto, Personaje $personaje)
    {
        $this->verificarOwner($proyecto);

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

    /**
     * Elimina un personaje del proyecto.
     *
     * @param Proyecto  $proyecto
     * @param Personaje $personaje
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPersonaje(Proyecto $proyecto, Personaje $personaje)
    {
        $this->verificarOwner($proyecto);

        $personaje->delete();

        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Personaje eliminado.');
    }

    // ── ÍTEMS ──────────────────────────────────────────────────

    /**
     * Crea un nuevo ítem en el proyecto.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeItem(Request $request, Proyecto $proyecto)
    {
        $this->verificarOwner($proyecto);

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

    /**
     * Actualiza los datos de un ítem existente.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @param Item     $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(Request $request, Proyecto $proyecto, Item $item)
    {
        $this->verificarOwner($proyecto);

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

    /**
     * Elimina un ítem del proyecto.
     *
     * @param Proyecto $proyecto
     * @param Item     $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyItem(Proyecto $proyecto, Item $item)
    {
        $this->verificarOwner($proyecto);

        $item->delete();

        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Ítem eliminado.');
    }

    // ── DIÁLOGOS ───────────────────────────────────────────────

    /**
     * Crea una nueva línea de diálogo en el proyecto.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDialogo(Request $request, Proyecto $proyecto)
    {
        $this->verificarOwner($proyecto);

        $data = $request->validate([
            'id_conversacion' => 'required|string|max:100',
            'orden'           => 'required|integer|min:0',
            'personaje_id'    => 'required|string|max:100',
            'texto'           => 'required|string',
        ]);

        Dialogo::create(array_merge($data, ['proyecto_id' => $proyecto->id]));

        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo creado.');
    }

    /**
     * Actualiza una línea de diálogo existente.
     *
     * @param Request  $request
     * @param Proyecto $proyecto
     * @param Dialogo  $dialogo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDialogo(Request $request, Proyecto $proyecto, Dialogo $dialogo)
    {
        $this->verificarOwner($proyecto);

        $data = $request->validate([
            'id_conversacion' => 'required|string|max:100',
            'orden'           => 'required|integer|min:0',
            'personaje_id'    => 'required|string|max:100',
            'texto'           => 'required|string',
        ]);

        $dialogo->update($data);

        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo actualizado.');
    }

    /**
     * Elimina una línea de diálogo del proyecto.
     *
     * @param Proyecto $proyecto
     * @param Dialogo  $dialogo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyDialogo(Proyecto $proyecto, Dialogo $dialogo)
    {
        $this->verificarOwner($proyecto);

        $dialogo->delete();

        return redirect()->route('proyecto.predefinicion', $proyecto->id)->with('success', 'Diálogo eliminado.');
    }
}
