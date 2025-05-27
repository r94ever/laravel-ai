<?php

namespace R94ever\PHPAI\Providers\Gemini;

use R94ever\PHPAI\Objects\ChatMessage;

class ChatRolesMapper
{
    private static array $rolesMap = [
        ChatMessage::ROLE_USER => 'user',
        ChatMessage::ROLE_INSTRUCTOR => 'assistant',
        ChatMessage::ROLE_ASSISTANT => 'model',
    ];

    public static function convert(string $role): string
    {
        return self::$rolesMap[$role] ?? 'user';
    }
}