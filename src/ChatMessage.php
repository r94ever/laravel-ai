<?php

namespace R94ever\PHPAI;

class ChatMessage
{
    public function __construct(protected string $message) {}

    public function getMessage(): string
    {
        return $this->message;
    }
}