<?php

namespace R94ever\PHPAI\Tests\Unit\Objects;

use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Tests\TestCase;

class ChatMessageTest extends TestCase
{
    public function test_it_can_be_instantiated_with_default_role()
    {
        $message = new ChatMessage('Test message');
        
        $this->assertEquals('Test message', $message->getMessage());
        $this->assertEquals(ChatMessage::ROLE_USER, $message->getRole());
    }

    public function test_it_can_be_instantiated_with_specific_role()
    {
        $message = new ChatMessage('Test message', ChatMessage::ROLE_ASSISTANT);
        
        $this->assertEquals(ChatMessage::ROLE_ASSISTANT, $message->getRole());
    }

    public function test_it_defaults_to_user_role_for_invalid_role()
    {
        $message = new ChatMessage('Test message', 'invalid_role');
        
        $this->assertEquals(ChatMessage::ROLE_USER, $message->getRole());
    }

    public function test_it_can_set_and_get_message()
    {
        $message = new ChatMessage('Original message');
        $message->setMessage('Updated message');
        
        $this->assertEquals('Updated message', $message->getMessage());
    }

    public function test_it_can_set_and_get_valid_role()
    {
        $message = new ChatMessage('Test message');
        $message->setRole(ChatMessage::ROLE_INSTRUCTOR);
        
        $this->assertEquals(ChatMessage::ROLE_INSTRUCTOR, $message->getRole());
    }

    public function test_it_keeps_current_role_when_setting_invalid_role()
    {
        $message = new ChatMessage('Test message', ChatMessage::ROLE_ASSISTANT);
        $message->setRole('invalid_role');
        
        $this->assertEquals(ChatMessage::ROLE_ASSISTANT, $message->getRole());
    }

    public function test_it_can_create_user_message()
    {
        $message = ChatMessage::user('User message');
        
        $this->assertEquals('User message', $message->getMessage());
        $this->assertEquals(ChatMessage::ROLE_USER, $message->getRole());
    }

    public function test_it_can_create_assistant_message()
    {
        $message = ChatMessage::assistant('Assistant message');
        
        $this->assertEquals('Assistant message', $message->getMessage());
        $this->assertEquals(ChatMessage::ROLE_ASSISTANT, $message->getRole());
    }

    public function test_it_can_create_instructor_message()
    {
        $message = ChatMessage::instructor('Instructor message');
        
        $this->assertEquals('Instructor message', $message->getMessage());
        $this->assertEquals(ChatMessage::ROLE_INSTRUCTOR, $message->getRole());
    }
}
