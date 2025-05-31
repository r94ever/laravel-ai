<?php

namespace R94ever\PHPAI\Tests\Unit\Events;

use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Events\ChatMessageSent;
use R94ever\PHPAI\Events\ChatResponseReceived;
use R94ever\PHPAI\Tests\TestCase;

class ChatEventsTest extends TestCase
{
    public function test_chat_message_sent_event_constructor()
    {
        $message = 'Test message';
        $userId = '123';

        $event = new ChatMessageSent($message, $userId);

        $this->assertEquals($message, $event->message);
        $this->assertEquals($userId, $event->userId);
    }

    public function test_chat_message_sent_event_without_user_id()
    {
        $message = 'Test message';

        $event = new ChatMessageSent($message);

        $this->assertEquals($message, $event->message);
        $this->assertNull($event->userId);
    }

    public function test_chat_response_received_event_constructor()
    {
        $message = 'Test message';
        $userId = '123';
        $response = \Mockery::mock(AITextGeneratorResponse::class);

        $event = new ChatResponseReceived($message, $response, $userId);

        $this->assertEquals($message, $event->message);
        $this->assertSame($response, $event->response);
        $this->assertEquals($userId, $event->userId);
    }

    public function test_chat_response_received_event_without_user_id()
    {
        $message = 'Test message';
        $response = \Mockery::mock(AITextGeneratorResponse::class);

        $event = new ChatResponseReceived($message, $response);

        $this->assertEquals($message, $event->message);
        $this->assertSame($response, $event->response);
        $this->assertNull($event->userId);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
