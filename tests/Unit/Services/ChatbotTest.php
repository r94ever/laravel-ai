<?php

namespace R94ever\PHPAI\Tests\Unit\Services;

use Mockery;
use R94ever\PHPAI\ChatMessage;
use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Events\ChatMessageSent;
use R94ever\PHPAI\Events\ChatResponseReceived;
use R94ever\PHPAI\Exceptions\ChatbotException;
use R94ever\PHPAI\Facades\AI;
use R94ever\PHPAI\Services\Chatbot;
use R94ever\PHPAI\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class ChatbotTest extends TestCase
{
    public function test_it_sends_message_and_gets_response()
    {
        Event::fake();

        $provider = Mockery::mock(AIProvider::class);

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

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
