<?php

namespace R94ever\PHPAI\Tests\Unit\Objects;

use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Tests\TestCase;

class ChatHistoryTest extends TestCase
{
    public function test_it_can_be_instantiated_empty()
    {
        $history = new ChatHistory();
        $this->assertFalse($history->hasMessages());
        $this->assertCount(0, $history->getMessages());
    }

    public function test_it_can_add_single_message()
    {
        $history = new ChatHistory();
        $message = new ChatMessage('Hello');

        $history->addMessage($message);

        $this->assertTrue($history->hasMessages());
        $this->assertCount(1, $history->getMessages());
        $this->assertSame($message, $history->getMessages()[0]);
    }

    public function test_it_can_add_multiple_messages()
    {
        $message1 = new ChatMessage('Hello');
        $message2 = new ChatMessage('How are you?', ChatMessage::ROLE_ASSISTANT);
        $message3 = new ChatMessage('I am fine', ChatMessage::ROLE_USER);

        $history = new ChatHistory([$message1, $message2]);
        $history->addMessage($message3);

        $this->assertTrue($history->hasMessages());
        $this->assertCount(3, $history->getMessages());
        $this->assertEquals([$message1, $message2, $message3], $history->getMessages());
    }

    public function test_it_ignores_non_chat_message_objects()
    {
        $history = new ChatHistory();
        $message = new ChatMessage('Hello');

        $history->addMessages([
            $message,
            'not a chat message',
            new \stdClass(),
            null
        ]);

        $this->assertTrue($history->hasMessages());
        $this->assertCount(1, $history->getMessages());
        $this->assertSame($message, $history->getMessages()[0]);
    }
}
