<?php

namespace R94ever\PHPAI\Providers\Gemini;

use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Providers\Gemini\Configs\GenerationConfig;

class GeminiProvider implements AIProvider
{
    private AIGenerationConfig $textGenerationConfig;

    public function getConfiguration(): AIGenerationConfig
    {
        return $this->textGenerationConfig ??= new GenerationConfig();
    }

    public function chat(ChatMessage $chatMessage): AITextGeneratorResponse
    {
        return (new TextGeneration($chatMessage, $this->textGenerationConfig))->generate();
    }
}