<?php
use App\Models\Proyecto;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
Broadcast::channel('proyecto.{id}', function ($user, $id) {
   
    return Proyecto::where('id', $id)
        ->whereHas('usuarios', function ($query) use ($user) {
            $query->where('users.id', $user->id); 
        })->exists();
});

