<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Providers\Gemini\ChatRolesMapper;
use R94ever\PHPAI\Tests\TestCase;

class ChatRolesMapperTest extends TestCase
{
    public function test_it_maps_user_role_correctly()
    {
        $this->assertEquals('user', ChatRolesMapper::convert(ChatMessage::ROLE_USER));
    }

    public function test_it_maps_assistant_role_correctly()
    {
        $this->assertEquals('model', ChatRolesMapper::convert(ChatMessage::ROLE_ASSISTANT));
    }

    public function test_it_maps_instructor_role_correctly()
    {
        $this->assertEquals('assistant', ChatRolesMapper::convert(ChatMessage::ROLE_INSTRUCTOR));
    }

    public function test_it_defaults_to_user_role_for_invalid_role()
    {
        $this->assertEquals('user', ChatRolesMapper::convert('invalid_role'));
    }
}
