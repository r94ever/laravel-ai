<?php

namespace R94ever\PHPAI\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;

class ChatResponseReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $message, public AITextGeneratorResponse $response, public ?string $userId = null)
    {
        //
    }
}
