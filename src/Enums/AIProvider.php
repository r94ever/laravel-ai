<?php

namespace R94ever\PHPAI\Enums;

use InvalidArgumentException;
use R94ever\PHPAI\Contracts\AIProvider as AIProviderContract;
use R94ever\PHPAI\Providers\Gemini\GeminiProvider;

enum AIProvider: string
{
    case Gemini = 'gemini';

    public function createProvider(): AIProviderContract
    {
        return match($this->value) {
            'gemini' => new GeminiProvider(),
            default => throw new InvalidArgumentException("Unsupported provider: $this->name"),
        };
    }
}