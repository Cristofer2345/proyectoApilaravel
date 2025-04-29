<?php

namespace App\Events;

use App\Models\Proyecto;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProyectoEventoTimeReal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proyecto;
    public $accion;

    public function __construct(Proyecto $proyecto, $accion)
    {
        $this->proyecto = $proyecto;
        $this->accion = $accion;

        Log::info("🔔 ProyectoEventoTimeReal: {$accion} - Proyecto ID: {$proyecto->id}");
    }

    public function broadcastOn()
    {
        return new Channel('proyectos'); // Canal general
    }

    public function broadcastAs()
    {
        return 'proyecto.evento';
    }
}
