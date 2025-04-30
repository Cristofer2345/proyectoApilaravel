<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tarea;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class TareaActualizadaTimeReal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tarea;
    public $accion;

    public function __construct(Tarea $tarea, $accion)
    {
       
        $this->tarea = $tarea;
        $this->accion = $accion;

        Log::info("🔔 TareaEventoTimeReal: {$accion} - Tarea ID: {$tarea->id}");
    }

    public function broadcastOn()
    {
        return new Channel('proyecto.' . $this->tarea->id_proyecto);
    }

    public function broadcastAs()
    {
        return 'tarea.actualizada';
    }
}
