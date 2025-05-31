<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini\Configs;

use R94ever\PHPAI\Exceptions\ChatbotException;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Providers\Gemini\ChatModel;
use R94ever\PHPAI\Providers\Gemini\Configs\GenerationConfig;
use R94ever\PHPAI\Tests\TestCase;

class GenerationConfigTest extends TestCase
{
    private GenerationConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new GenerationConfig();
    }

    public function test_it_can_set_and_get_instruction()
    {
        $instruction = new ChatMessage('Test instruction', ChatMessage::ROLE_INSTRUCTOR);
        
        $this->config->setInstruction($instruction);
        
        $this->assertSame($instruction, $this->config->getInstruction());
    }

    public function test_it_can_set_and_get_chat_model()
    {
        $model = ChatModel::Gemini2_5Flash;
        
        $this->config->setChatModel($model);
        
        $this->assertSame($model, $this->config->getChatModel());
    }

    public function test_it_can_set_and_get_temperature()
    {
        $temperature = 0.7;
        
        $this->config->setTemperature($temperature);
        
        $this->assertEquals($temperature, $this->config->getTemperature());
    }

    public function test_it_can_set_and_get_max_output_tokens()
    {
        $tokens = 1000;
        
        $this->config->setMaxOutputTokens($tokens);
        
        $this->assertEquals($tokens, $this->config->getMaxOutputTokens());
    }

    public function test_it_throws_exception_for_invalid_max_output_tokens()
    {
        $this->expectException(ChatbotException::class);
        $this->config->setMaxOutputTokens(0);
    }

    public function test_it_can_set_and_get_top_p()
    {
        $topP = 0.8;
        
        $this->config->setTopP($topP);
        
        $this->assertEquals($topP, $this->config->getTopP());
    }

    public function test_it_throws_exception_for_invalid_top_p()
    {
        $this->expectException(ChatbotException::class);
        $this->config->setTopP(2.0);
    }

    public function test_it_can_set_and_get_top_k()
    {
        $topK = 20;
        
        $this->config->setTopK($topK);
        
        $this->assertEquals($topK, $this->config->getTopK());
    }

    public function test_it_throws_exception_for_invalid_top_k()
    {
        $this->expectException(ChatbotException::class);
        $this->config->setTopK(-1);
    }

    public function test_it_can_set_and_get_chat_history()
    {
        $history = new ChatHistory([
            new ChatMessage('Hello'),
            new ChatMessage('Hi there!', ChatMessage::ROLE_ASSISTANT)
        ]);
        
        $this->config->setChatHistory($history);
        
        $this->assertSame($history, $this->config->getChatHistory());
    }

    public function test_it_returns_empty_chat_history_when_not_set()
    {
        $history = $this->config->getChatHistory();
        
        $this->assertInstanceOf(ChatHistory::class, $history);
        $this->assertFalse($history->hasMessages());
    }

    public function test_it_has_default_values()
    {
        $this->assertEquals(1.0, $this->config->getTemperature());
        $this->assertEquals(800, $this->config->getMaxOutputTokens());
        $this->assertEquals(0.8, $this->config->getTopP());
        $this->assertEquals(10, $this->config->getTopK());
        $this->assertNull($this->config->getInstruction());
    }
}
