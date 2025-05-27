<?php

namespace R94ever\PHPAI\Contracts;

use R94ever\PHPAI\ChatMessage;

interface AIProvider
{
    public function chat(ChatMessage $chatMessage): AITextGeneratorResponse;

    public function getConfiguration(): AITextGenerationConfig;
}