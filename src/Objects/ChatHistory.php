<?php

namespace R94ever\PHPAI\Objects;

class ChatHistory
{
    /**
     * @var array<ChatMessage>
     */
    private array $messages = [];

    /**
     * ChatHistory constructor.
     *
     * @param array<ChatMessage> $messages
     */
    public function __construct(array $messages = [])
    {
        $this->addMessages($messages);
    }

    /**
     * Get all chat messages.
     *
     * @return array<ChatMessage>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Get the last chat message.
     *
     * @param ChatMessage $message
     * @return ChatHistory
     */
    public function addMessage(ChatMessage $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Add multiple chat messages.
     *
     * @param array<ChatMessage> $messages
     */
    public function addMessages(array $messages): void
    {
        foreach ($messages as $message) {
            if ($message instanceof ChatMessage) {
                $this->addMessage($message);
            }
        }
    }

    /**
     * Check if there are any chat messages in history.
     *
     * @return bool
     */
    public function hasMessages(): bool
    {
        return !empty($this->messages);
    }
}