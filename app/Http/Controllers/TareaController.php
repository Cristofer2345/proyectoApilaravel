<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\User;

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
            'estado' => 'required|in:pendiente,en_progreso,completada',
            'id_usuario' => 'required|exists:users,id',
            'id_proyecto' => 'required|exists:proyectos,id'
        ]);

        $tarea = Tarea::create($request->all());

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

        return response()->json($tarea);
    }

    public function destroy($id)
    {
        Tarea::destroy($id);
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
