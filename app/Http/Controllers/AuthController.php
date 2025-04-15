<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
// Registro de usuario
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'credencial' => 'required|string|max:255',
        'password' => 'required|string|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'credencial' => $request->credencial,
        'password' => Hash::make($request->password) // Contraseña encriptada
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
        'message' => 'Usuario registrado correctamente'
    ], 201);
}

// Inicio de sesión
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales son incorrectas.']
        ]);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
        'message' => 'Inicio de sesión exitoso'
    ], 200);
}

// Cerrar sesión
public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
}

public function getUser()
{
    $users = User::select('id', 'name')->get();

    if ($users->isEmpty()) {
        return response()->json(['message' => 'No se encontraron usuarios.'], 404);
    }

    return response()->json($users, 200);
}
}
