<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Usuario;

class cUsuarioDepartamento extends Controller
{
    // Asignar un usuario a un departamento
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'department_id' => 'required|exists:departamentos,id',
        ]);

        $departamento = Departamento::findOrFail($request->department_id);
        $departamento->usuarios()->syncWithoutDetaching($request->user_id);

        return response()->json([
            'message' => 'Usuario asignado al departamento correctamente.'
        ]);
    }

    // Quitar un usuario de un departamento
    public function remove(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'department_id' => 'required|exists:departamentos,id',
        ]);

        $departamento = Departamento::findOrFail($request->department_id);
        $departamento->usuarios()->detach($request->user_id);

        return response()->json([
            'message' => 'Usuario removido del departamento correctamente.'
        ]);
    }
}
