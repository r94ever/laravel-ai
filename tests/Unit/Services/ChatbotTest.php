<?php

namespace R94ever\PHPAI\Tests\Unit\Services;

use Illuminate\Support\Facades\Event;
use Mockery;
use R94ever\PHPAI\Contracts\ChatbotProvider;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Events\ChatMessageSent;
use R94ever\PHPAI\Events\ChatResponseReceived;
use R94ever\PHPAI\Exceptions\ChatbotException;
use R94ever\PHPAI\Facades\AI;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Services\Chatbot;
use R94ever\PHPAI\Tests\TestCase;

class ChatbotTest extends TestCase
{
    public function test_it_sends_message_and_gets_response()
    {
        Event::fake();

        $provider = Mockery::mock(ChatbotProvider::class);

        $message = "Hello, how are you?";
        $expectedResponse = "I'm doing great, thanks for asking!";

        $mockResponse = Mockery::mock(AITextGeneratorResponse::class);
        $mockResponse->shouldReceive('getMessage')->andReturn($expectedResponse);

        $provider->shouldReceive('chat')
            ->withArgs(function ($chatMessage) use ($message) {
                return $chatMessage instanceof ChatMessage
                    && $chatMessage->getMessage() === $message;
            })
            ->andReturn($mockResponse);

        $response = AI::chatbot($provider)->chat($message);

        $this->assertSame($mockResponse, $response);
        
        Event::assertDispatched(ChatMessageSent::class, function ($event) use ($message) {
            return $event->message === $message;
        });
        
        Event::assertDispatched(ChatResponseReceived::class, function ($event) use ($mockResponse) {
            return $event->response === $mockResponse;
        });
    }

    public function test_it_handles_empty_message()
    {
        $this->expectException(ChatbotException::class);
        AI::chatbot()->chat('');
        AI::chatbot()->chat('  ');
        AI::chatbot()->chat("\n\n\r\t");
    }

    public function test_it_uses_config_provider()
    {
        $this->assertInstanceOf(Chatbot::class, AI::chatbot());
    }

    public function test_it_can_set_chat_history()
    {
        $provider = Mockery::mock(ChatbotProvider::class);
        $config = Mockery::mock(AIGenerationConfig::class);

        $provider->shouldReceive('getConfiguration')->andReturn($config);
        $config->shouldReceive('setChatHistory')->once()->andReturnSelf();

        $history = new ChatHistory([
            new ChatMessage('Hello'),
            new ChatMessage('Hi there!', ChatMessage::ROLE_ASSISTANT)
        ]);

        $chatbot = new Chatbot($provider);
        $result = $chatbot->withHistory($history);

        $this->assertSame($chatbot, $result);
    }

    public function test_it_uses_chat_history_in_conversation()
    {
        Event::fake();

        $provider = Mockery::mock(ChatbotProvider::class);
        $config = Mockery::mock(AIGenerationConfig::class);
        $history = new ChatHistory([
            new ChatMessage('What is your name?'),
            new ChatMessage('My name is AI Assistant', ChatMessage::ROLE_ASSISTANT)
        ]);

        $provider->shouldReceive('getConfiguration')->andReturn($config);
        $config->shouldReceive('setChatHistory')->once()->with($history)->andReturnSelf();

        $message = "Nice to meet you!";
        $expectedResponse = "Nice to meet you too!";

        $mockResponse = Mockery::mock(AITextGeneratorResponse::class);
        $mockResponse->shouldReceive('getMessage')->andReturn($expectedResponse);

        $provider->shouldReceive('chat')
            ->withArgs(function ($chatMessage) use ($message) {
                return $chatMessage instanceof ChatMessage
                    && $chatMessage->getMessage() === $message;
            })
            ->andReturn($mockResponse);

        $response = AI::chatbot($provider)
            ->withHistory($history)
            ->chat($message);

        $this->assertSame($mockResponse, $response);
        
        Event::assertDispatched(ChatMessageSent::class, function ($event) use ($message) {
            return $event->message === $message;
        });
        
        Event::assertDispatched(ChatResponseReceived::class, function ($event) use ($mockResponse) {
            return $event->response === $mockResponse;
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
