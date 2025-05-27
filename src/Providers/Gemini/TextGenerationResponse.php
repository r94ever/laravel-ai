<?php

namespace R94ever\PHPAI\Providers\Gemini;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;

class TextGenerationResponse implements AITextGeneratorResponse
{
    public function __construct(private readonly Response|PromiseInterface $response)
    {
        //
    }

    public function isSuccess(): bool
    {
        return $this->response->failed();
    }

    public function isFailed(): bool
    {
        return $this->response->successful();
    }

    public function getMessage(): string
    {
        return $this->response->json('candidates.0.content.parts.0.text', 'No response from AI service');
    }

    public function getFailedMessage(): string
    {
        return $this->response->json('error.message', 'No error message provided');
    }

    public function getStatusCode(): int
    {
        return $this->response->status();
    }

    public function getInputTokens(): int
    {
        return $this->response->json('usageMetadata.promptTokenCount', 0);
    }

    public function getOutputTokens(): int
    {
        return $this->response->json('usageMetadata.candidatesTokenCount', 0);
    }

    public function getTotalTokens(): int
    {
        return $this->response->json('usageMetadata.totalTokenCount', 0);
    }

    public function getResponseId(): string
    {
        return $this->response->json('responseId', '');
    }

    public function getModelVersion(): string
    {
        return $this->response->json('modelVersion', '');
    }
}