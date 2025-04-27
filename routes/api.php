<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\TareaController;

Route::middleware('auth:sanctum')->group(function () {
    // Rutas para proyectos
    Route::prefix('proyectos')->group(function () {
        Route::get('/', [ProyectoController::class, 'index']);
        Route::post('/', [ProyectoController::class, 'store']);
        Route::get('/{proyecto}', [ProyectoController::class, 'show']);
        Route::put('/{proyecto}', [ProyectoController::class, 'update']);
        Route::delete('/{proyecto}', [ProyectoController::class, 'destroy']);
    Route::post('/{proyecto}/asignar-usuario', [ProyectoController::class, 'asignarUsuario']);
    });

 
    // Rutas para tareas
    Route::prefix('tareas')->group(function () {
        Route::get('/', [TareaController::class, 'index']);
        Route::post('/', [TareaController::class, 'store']);
        Route::get('/{tarea}', [TareaController::class, 'show']);
        Route::put('/{tarea}', [TareaController::class, 'update']);
        Route::delete('/{tarea}', [TareaController::class, 'destroy']);
        Route::post('/{tarea}/assign', [TareaController::class, 'assignUser']);
        Route::post('/{tarea}/unassign', [TareaController::class, 'unassignUser']);
        Route::get('/proyecto/{proyectoId}', [TareaController::class, 'tareasPorProyecto']);

    });
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [AuthController::class, 'getUser']);
    });
});

// Rutas de autenticación
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);