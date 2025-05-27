<?php

namespace R94ever\PHPAI;

use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Services\Chatbot;

class AI
{
    private AIProvider $defaultProvider;

    public function __construct(AIProvider $defaultProvider)
    {
        $this->defaultProvider = $defaultProvider;
    }

    public function chatbot(?AIProvider $provider = null): Chatbot
    {
        return new Chatbot($provider ?? $this->defaultProvider);
    }
}