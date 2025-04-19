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

    public function __construct(Tarea $tarea)
    {

        $this->tarea = $tarea;
        log::info('🔔 Evento de tarea actualizada: ');
    }

    public function broadcastOn()
    {
        Log::info('🔔 Enviando evento a canal: proyecto.' . $this->tarea->id_proyecto);
        return new Channel('proyecto.' . $this->tarea->id_proyecto);
    }

    public function broadcastAs()
    {
        return 'tarea.actualizada';
    }
}
