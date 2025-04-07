<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Lista;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = ['titulo'];


    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'proyectos_users', 'proyecto_id', 'usuario_id');

    }

    // Relación uno a muchos con tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }
}

