<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = ['titulo_tarea', 'descripcion', 'estado', 'id_usuario', 'id_proyecto'];
    protected $table = 'tareas';


   public function proyecto()
   {
       return $this->belongsTo(Proyecto::class, 'id_lista', 'id');
   }
    public function usuarios()
    {
         return $this->belongsTo(User::class, 'tarea_users', 'id', 'id');
    }
    
}
