<?php

namespace R94ever\PHPAI;

use R94ever\PHPAI\Contracts\ChatbotProvider;
use R94ever\PHPAI\Services\Chatbot;
use R94ever\PHPAI\Services\ChatbotProvidersManager;
use RuntimeException;

class AI
{
    public function __construct(private readonly ChatbotProvidersManager $chatbotProvidersManager)
    {
        //
    }

    public function chatbot(?ChatbotProvider $provider = null): Chatbot
    {
        if ($this->chatbotProvidersManager->isEmpty()) {
            throw new RuntimeException('No chatbot providers registered. Please register at least one provider.');
        }

        return new Chatbot($provider ?? $this->chatbotProvidersManager->getDefault());
    }
}
