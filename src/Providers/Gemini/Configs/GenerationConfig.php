<?php

namespace R94ever\PHPAI\Providers\Gemini\Configs;

use BackedEnum;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Exceptions\ChatbotException;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;

class GenerationConfig implements AIGenerationConfig
{
    /**
     * The temperature for text generation.
     * A higher value (e.g., 1.0) makes the output more random, while a lower value (e.g., 0.2)
     * makes it more focused and deterministic.
     *
     * @var int|float
     */
    private int|float $temperature = 1.0;

    /**
     * The maximum number of tokens to generate in the output.
     * This limits the length of the generated text.
     *
     * @var int
     */
    private int $maxOutputTokens = 800;

    /**
     * The top P value for nucleus sampling.
     * It controls the diversity of the generated text by limiting the selection to the top P percentage
     * of the probability mass.
     * A value of 0.8 means that only the top 80% of the probability mass is considered.
     *
     * @var int|float
     */
    private int|float $topP = 0.8;

    /**
     * The top K value for top-k sampling.
     * It limits the selection to the top K most probable tokens.
     * A value of 10 means that only the top 10 tokens are considered for generation.
     *
     * @var int
     */
    private int $topK = 10;

    /**
     * The instruction message to guide the AI in generating text.
     * This can be a system message or any other instruction that helps shape the response.
     *
     * @var ChatMessage|null
     */
    private ?ChatMessage $instruction = null;

    /**
     * The chat model to be used for text generation.
     * This can be a specific model identifier or configuration that determines how the AI generates text.
     *
     * @var BackedEnum
     */
    private BackedEnum $chatModel;

    /**
     * The chat history to maintain context during text generation.
     * This can include previous messages and responses to provide context for the AI.
     *
     * @var ChatHistory
     */
    private ChatHistory $chatHistory;

    /**
     * Set the instruction message for the AI text generation.
     *
     * @param ChatMessage $instruction The instruction message to set.
     * @return AIGenerationConfig
     */
    public function setInstruction(ChatMessage $instruction): AIGenerationConfig
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * Get the instruction message for the AI text generation.
     *
     * @return ChatMessage|null The instruction message, or null if not set.
     */
    public function getInstruction(): ?ChatMessage
    {
        return $this->instruction;
    }

    /**
     * Set the chat model for text generation.
     *
     * @param BackedEnum $chatModel The chat model to set.
     * @return AIGenerationConfig
     */
    public function setChatModel(BackedEnum $chatModel): AIGenerationConfig
    {
        $this->chatModel = $chatModel;

        return $this;
    }

    /**
     * Get the chat model for text generation.
     *
     * @return BackedEnum The chat model.
     */
    public function getChatModel(): BackedEnum
    {
        return $this->chatModel;
    }

    /**
     * Set the temperature for text generation.
     *
     * @param float|int $temperature The temperature value to set.
     * @return AIGenerationConfig
     */
    public function setTemperature(float|int $temperature): AIGenerationConfig
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get the temperature for text generation.
     *
     * @return float|int The temperature value.
     */
    public function getTemperature(): float|int
    {
        return $this->temperature;
    }

    /**
     * Set the maximum number of output tokens for text generation.
     *
     * @param float|int $maxOutputTokens The maximum output tokens to set.
     * @return AIGenerationConfig
     * @throws ChatbotException
     */
    public function setMaxOutputTokens(float|int  $maxOutputTokens): AIGenerationConfig
    {
        if ($maxOutputTokens <= 0) {
            throw ChatbotException::invalidMaxOutputTokens($maxOutputTokens);
        }

        $this->maxOutputTokens = (int) $maxOutputTokens;

        return $this;
    }

    /**
     * Get the maximum number of output tokens for text generation.
     *
     * @return int The maximum output tokens.
     */
    public function getMaxOutputTokens(): int
    {
        return $this->maxOutputTokens;
    }

    /**
     * Set the top P value for nucleus sampling.
     *
     * @param float|int $topP The top P value to set.
     * @return AIGenerationConfig
     * @throws ChatbotException
     */
    public function setTopP(float|int  $topP): AIGenerationConfig
    {
        if ($topP < 0 || $topP > 1) {
            throw ChatbotException::invalidTopP($topP);
        }

        $this->topP = $topP;

        return $this;
    }

    /**
     * Get the top P value for nucleus sampling.
     *
     * @return float|int The top P value.
     */
    public function getTopP(): float|int
    {
        return $this->topP;
    }

    /**
     * Set the top K value for top-k sampling.
     *
     * @param float|int $topK The top K value to set.
     * @return AIGenerationConfig
     * @throws ChatbotException
     */
    public function setTopK(float|int  $topK): AIGenerationConfig
    {
        if ($topK < 0) {
            throw ChatbotException::invalidTopK($topK);
        }

        $this->topK = (int) $topK;

        return $this;
    }

    /**
     * Get the top K value for top-k sampling.
     *
     * @return int The top K value.
     */
    public function getTopK(): int
    {
        return $this->topK;
    }

    /**
     * Get the chat history for maintaining context during text generation.
     *
     * @return ChatHistory The chat history object.
     */
    public function getChatHistory(): ChatHistory
    {
        return $this->chatHistory ?? new ChatHistory();
    }

    /**
     * Set the chat history for maintaining context during text generation.
     *
     * @param ChatHistory $chatHistory The chat history object to set.
     * @return AIGenerationConfig
     */
    public function setChatHistory(ChatHistory $chatHistory): AIGenerationConfig
    {
        $this->chatHistory = $chatHistory;

        return $this;
    }
}