<?php

namespace R94ever\PHPAI\Contracts;

interface AITextGeneratorResponse
{
    public function isSuccess(): bool;

    public function isFailed(): bool;

    public function getMessage(): string;

    public function getFailedMessage(): string;

    public function getStatusCode(): int;

    public function getInputTokens(): int;

    public function getOutputTokens(): int;

    public function getTotalTokens(): int;
}