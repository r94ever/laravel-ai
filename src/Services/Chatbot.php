<?php

namespace R94ever\PHPAI\Services;

use BackedEnum;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use R94ever\PHPAI\ChatMessage;
use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Events\ChatMessageSent;
use R94ever\PHPAI\Events\ChatResponseReceived;
use R94ever\PHPAI\Exceptions\ChatbotException;

class Chatbot
{
    /**
     * Create a new Chatbot instance.
     *
     * @param AIProvider $provider The AI provider to use for chat operations.
     */
    public function __construct(readonly private AIProvider $provider) {}

    /**
     * Set the instruction message to be used as a system prompt.
     *
     * @param string|ChatMessage $instruction The instruction message.
     * @return self
     */
    public function withInstruction(string|ChatMessage $instruction): self
    {
        if (is_string($instruction)) {
            $instruction = new ChatMessage($instruction);
        }

        $this->provider->getConfiguration()->setInstruction($instruction);

        return $this;
    }

    /**
     * Set the chat model to be used for generating responses.
     *
     * @param BackedEnum $chatModel The chat model to use.
     * @return self
     */
    public function useModel(BackedEnum $chatModel): self
    {
        $this->provider->getConfiguration()->setChatModel($chatModel);

        return $this;
    }

    /**
     * Apply a configuration callback to the AI provider's configuration.
     *
     * @param callable $callback The callback to apply to the configuration.
     * @return self
     */
    public function withConfig(callable $callback): self
    {
        tap($this->provider->getConfiguration(), function ($config) use ($callback) {
            $callback($config);
        });

        return $this;
    }

    /**
     * Send a chat message to the AI provider and receive a response.
     *
     * @param string $message The message to send.
     * @param int|null $userId Optional user ID for tracking.
     * @return AITextGeneratorResponse The generated response from the AI provider.
     * @throws ChatbotException If the message is empty.
     */
    public function chat(string $message, ?int $userId = null): AITextGeneratorResponse
    {
        if (!Str::squish($message)) {
            throw ChatbotException::emptyChatMessage();
        }

        Event::dispatch(new ChatMessageSent($message, $userId));

        $chatMessage = new ChatMessage($message);

        $response = $this->provider->chat($chatMessage);

        Event::dispatch(new ChatResponseReceived($message, $response, $userId));

        return $response;
    }
}