<?php

namespace R94ever\PHPAI\Tests\Unit\Exceptions;

use R94ever\PHPAI\Exceptions\ChatbotException;
use R94ever\PHPAI\Tests\TestCase;

class ChatbotExceptionTest extends TestCase
{
    public function test_it_creates_empty_message_exception()
    {
        $exception = ChatbotException::emptyChatMessage();
        
        $this->assertInstanceOf(ChatbotException::class, $exception);
        $this->assertEquals('Chat message cannot be empty.', $exception->getMessage());
    }

    public function test_it_creates_invalid_max_output_tokens_exception()
    {
        $exception = ChatbotException::invalidMaxOutputTokens(0);
        
        $this->assertInstanceOf(ChatbotException::class, $exception);
        $this->assertEquals('Invalid max output tokens: 0. It must be a positive integer.', $exception->getMessage());
    }

    public function test_it_creates_invalid_top_p_exception()
    {
        $exception = ChatbotException::invalidTopP(2.0);
        
        $this->assertInstanceOf(ChatbotException::class, $exception);
        $this->assertEquals('Invalid top P value: 2. It must be between 0 and 1.', $exception->getMessage());
    }

    public function test_it_creates_invalid_top_k_exception()
    {
        $exception = ChatbotException::invalidTopK(-1);
        
        $this->assertInstanceOf(ChatbotException::class, $exception);
        $this->assertEquals('Invalid top K value: -1. It must be a non-negative integer.', $exception->getMessage());
    }
}
