<?php

namespace App\Http\Controllers;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\ProyectoEventoTimeReal;

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
        ]);
        broadcast(new ProyectoEventoTimeReal($proyecto, 'añadido'));


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
        broadcast(new ProyectoEventoTimeReal($proyecto, 'actualizado'));
        return response()->json($proyecto);
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id); 
        $proyectoData = $proyecto->toArray();  
    
        Proyecto::destroy($id);
        broadcast(new ProyectoEventoTimeReal((object) $proyectoData, 'eliminado'));
    
        return response()->json(['message' => 'Proyecto eliminado']);
    }

    public function asignarUsuarios(Request $request, $id)
{
    try {
        // Validar que usuario_id sea un array y cada valor exista en la tabla users
        $request->validate([
            'usuario_id' => 'required|array',
            'usuario_id.*' => 'exists:users,id',
        ]);

        $proyecto = Proyecto::findOrFail($id);

        // Filtra los usuarios que ya están asignados
        $usuariosNuevos = array_filter($request->usuario_id, function ($userId) use ($proyecto) {
            return !$proyecto->usuarios()->where('id', $userId)->exists();
        });

        // Asignar solo los usuarios que no estaban ya asignados
        $proyecto->usuarios()->attach($usuariosNuevos);

        return response()->json(['message' => 'Usuarios asignados al proyecto correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

    public function desasignarUsuarios(Request $request, $id)
{
    try {
        // Validar que usuario_id sea un array y cada valor exista en la tabla users
        $request->validate([
            'usuario_id' => 'required|array',
            'usuario_id.*' => 'exists:users,id',
        ]);

        $proyecto = Proyecto::findOrFail($id);

        // Filtra los usuarios que están asignados
        $usuariosAEliminar = array_filter($request->usuario_id, function ($userId) use ($proyecto) {
            return $proyecto->usuarios()->where('id', $userId)->exists();
        });

        // Desasignar solo los usuarios que estaban asignados
        $proyecto->usuarios()->detach($usuariosAEliminar);

        return response()->json(['message' => 'Usuarios desasignados del proyecto correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
    
}
