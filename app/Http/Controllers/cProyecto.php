<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class cProyecto extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Proyecto::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('proyectos');
    }
}
