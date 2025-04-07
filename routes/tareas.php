<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

Route::post('/tareas', [TareaController::class, 'store'])->middleware('auth:sanctum'); // Crear tarea
Route::get('/tareas', [TareaController::class, 'index'])->middleware('auth:sanctum'); // Obtener todas las tareas
Route::get('/tareas/{id}', [TareaController::class, 'show'])->middleware('auth:sanctum'); // Obtener tarea por ID
Route::put('/tareas/{id}', [TareaController::class, 'update'])->middleware('auth:sanctum'); // Actualizar tarea
Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->middleware('auth:sanctum'); // Eliminar tarea