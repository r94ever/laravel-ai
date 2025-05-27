<?php

namespace R94ever\PHPAI\Objects;

class ChatMessage
{
    const ROLE_USER = 'user';

    const ROLE_ASSISTANT = 'assistant';

    const ROLE_INSTRUCTOR = 'instructor';

    public function __construct(protected string $message, protected string $role = self::ROLE_USER)
    {
        if (!in_array($this->role, [self::ROLE_USER, self::ROLE_ASSISTANT, self::ROLE_INSTRUCTOR])) {
            $this->role = self::ROLE_USER;
        }
    }

    /**
     * Get the message content.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message content.
     *
     * @param string $message
     * @return ChatMessage
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the role of the message sender.
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the role of the message sender.
     *
     * @param string $role
     * @return ChatMessage
     */
    public function setRole(string $role): self
    {
        if (in_array($role, [self::ROLE_USER, self::ROLE_ASSISTANT, self::ROLE_INSTRUCTOR])) {
            $this->role = $role;
        } else {
            $this->role = self::ROLE_USER;
        }

        return $this;
    }

    /**
     * Create a new ChatMessage instance with the user role.
     *
     * @param string $message
     * @return self
     */
    public static function user(string $message): self
    {
        return new self($message, self::ROLE_USER);
    }

    /**
     * Create a new ChatMessage instance with the assistant role.
     *
     * @param string $message
     * @return self
     */
    public static function assistant(string $message): self
    {
        return new self($message, self::ROLE_ASSISTANT);
    }

    /**
     * Create a new ChatMessage instance with the system role.
     *
     * @param string $message
     * @return self
     */
    public static function instructor(string $message): self
    {
        return new self($message, self::ROLE_INSTRUCTOR);
    }
}