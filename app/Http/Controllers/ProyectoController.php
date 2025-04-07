<?php

namespace App\Http\Controllers;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        return Proyecto::with('usuarios')->get();

    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string'
        ]);

        $proyecto = Proyecto::create($request->only('titulo'));

        return response()->json($proyecto, 201);
    }

    public function show($id)
    {
        return Proyecto::with('usuarios')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->only('titulo'));

        return response()->json($proyecto);
    }

    public function destroy($id)
    {
        Proyecto::destroy($id);
        return response()->json(['message' => 'Proyecto eliminado']);
    }

    public function asignarUsuario(Request $request, $id)
    {
        try {
            $request->validate([
                'usuario_id' => 'required|exists:users,id'
            ]);

            $proyecto = Proyecto::findOrFail($id);

            if ($proyecto->usuarios()->where('id', $request->usuario_id)->exists()) {
                return response()->json(['error' => 'El usuario ya está asignado a este proyecto'], 400);
            }

            $proyecto->usuarios()->attach($request->usuario_id);

            return response()->json(['message' => 'Usuario asignado al proyecto']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
}
