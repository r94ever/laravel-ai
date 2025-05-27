<?php

namespace R94ever\PHPAI\Exceptions;

use Exception;

class ChatbotException extends Exception
{
    public static function emptyChatMessage(): self
    {
        return new self('Chat message cannot be empty.');
    }

    public static function invalidMaxOutputTokens(float|int $maxOutputTokens): self
    {
        return new self(sprintf('Invalid max output tokens: %s. It must be a positive integer.', $maxOutputTokens));
    }

    public static function invalidTopP(float|int $topP): self
    {
        return new self(sprintf('Invalid top P value: %s. It must be between 0 and 1.', $topP));
    }

    public static function invalidTopK(float|int $topK): self
    {
        return new self(sprintf('Invalid top K value: %s. It must be a non-negative integer.', $topK));
    }
}