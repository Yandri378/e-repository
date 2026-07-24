<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RepositorySettingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $kategori;
    public string $status;

    public function __construct(string $kategori, string $status)
    {
        $this->kategori = $kategori;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('repository-settings');
    }

    public function broadcastWith()
    {
        return [
            'kategori' => $this->kategori,
            'status' => $this->status,
            'updated_at' => now()->toIsoString(),
        ];
    }
}
