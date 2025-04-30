<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\User;
use App\Events\TareaActualizadaTimeReal;
use Illuminate\Support\Facades\Log; 

class TareaController extends Controller
{
    public function index()
    {
        return Tarea::with(['usuarios', 'proyecto'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_tarea' => 'required|string',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,en progreso,completada',
            'id_usuario' => 'required|exists:users,id',
            'id_proyecto' => 'required|exists:proyectos,id'
        ]);

        $tarea = Tarea::create($request->all());
        try {
            broadcast(new TareaActualizadaTimeReal($tarea,'añadido'));
            Log::info("Broadcast ejecutado con éxito");
        } catch (\Exception $e) {
            Log::error("Error al emitir evento: " . $e->getMessage());
        }

        return response()->json($tarea, 201);
    }

    public function show($id)
    {
        return Tarea::with(['usuarios', 'proyecto'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);
     
        $tarea->update($request->only('titulo_tarea', 'descripcion', 'estado', 'id_usuario', 'id_proyecto'));
        broadcast(new TareaActualizadaTimeReal($tarea,'actualizado'));
        return response()->json($tarea);
    }

    public function destroy($id)

    
    {
        $tarea = Tarea::findOrFail($id);
        Tarea::destroy($id);
        broadcast(new TareaActualizadaTimeReal($tarea, 'eliminado'));
        return response()->json(['message' => 'Tarea eliminada']);
    }

    public function tareasPorProyecto($proyectoId)
{
    return Tarea::with(['usuarios', 'proyecto'])->where('id_proyecto', $proyectoId)->get();
}
}
