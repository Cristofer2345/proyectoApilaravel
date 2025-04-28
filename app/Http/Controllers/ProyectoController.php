<?php

namespace App\Http\Controllers;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProyectoController extends Controller
{
    public function index()
    {
        return Proyecto::with('usuarios')->get();

    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    
        $request->validate([
            'titulo' => 'required|string'
        ]);
    
        $proyecto = Proyecto::create([
            'titulo' => $request->titulo,
            'created_by' => Auth::user()->id,
            Log::info("Proyecto creado por el usuario: " . Auth::user()->id)
        ]);
    

        return Proyecto::with('usuarios')->findOrFail(Auth::user()->id);
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
    public function desasignarUsuario(Request $request, $id)
{
    $request->validate([
        'usuario_id' => 'required|exists:users,id'
    ]);

    $proyecto = Proyecto::findOrFail($id);

    // Verifica que el usuario esté asignado
    if (! $proyecto->usuarios()->where('users.id', $request->usuario_id)->exists()) {
        return response()->json(['error' => 'El usuario no está asignado a este proyecto'], 400);
    }

    $proyecto->usuarios()->detach($request->usuario_id);

    return response()->json(['message' => 'Usuario desasignado del proyecto']);
}
    
}
