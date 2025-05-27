<?php

namespace R94ever\PHPAI\Providers\Gemini;

use R94ever\PHPAI\ChatMessage;
use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Contracts\AITextGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Providers\Gemini\Configs\TextGenerationConfig;

class GeminiProvider implements AIProvider
{
    private AITextGenerationConfig $textGenerationConfig;

    public function getConfiguration(): AITextGenerationConfig
    {
        return $this->textGenerationConfig ??= new TextGenerationConfig();
    }

    public function chat(ChatMessage $chatMessage): AITextGeneratorResponse
    {
        return (new TextGeneration($chatMessage, $this->textGenerationConfig))->generate();
    }
}