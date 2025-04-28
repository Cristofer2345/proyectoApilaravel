<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Lista;
use App\Models\User;
class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'created_by'];


    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'proyectos_users', 'proyecto_id', 'usuario_id');

    }
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
  
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }
}

