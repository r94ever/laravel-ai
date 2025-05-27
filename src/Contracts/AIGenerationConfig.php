<?php

namespace R94ever\PHPAI\Contracts;

use BackedEnum;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;

interface AIGenerationConfig
{
    public function getInstruction(): ?ChatMessage;

    public function setInstruction(ChatMessage $instruction): self;

    public function getChatModel(): BackedEnum;

    public function setChatModel(BackedEnum $chatModel): self;

    public function getTemperature(): int|float;

    public function setTemperature(int|float $temperature): self;

    public function getMaxOutputTokens(): float|int;

    public function setMaxOutputTokens(int $maxOutputTokens): self;

    public function getTopP(): float|int;

    public function setTopP(float|int $topP): self;

    public function getTopK(): float|int;

    public function setTopK(float|int $topK): self;

    public function getChatHistory(): ChatHistory;

    public function setChatHistory(ChatHistory $chatHistory): self;
}