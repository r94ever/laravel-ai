<?php

namespace R94ever\PHPAI\Providers\Gemini;

use Illuminate\Support\Facades\Http;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGenerator;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Objects\ChatMessage;

class TextGeneration implements AITextGenerator
{
    public function __construct(
        private readonly ChatMessage $userMessage,
        public readonly AIGenerationConfig $textGenerationConfig
    ) {
        //
    }

    private function generateRequestData(): string
    {
        $data = [
            'contents' => [
                ['parts' => [['text' => $this->userMessage->getMessage()]]]
            ],
            'generationConfig' => [
                'temperature' => $this->textGenerationConfig->getTemperature(),
                'maxOutputTokens' => $this->textGenerationConfig->getMaxOutputTokens(),
                'topP' => $this->textGenerationConfig->getTopP(),
                'topK' => $this->textGenerationConfig->getTopK(),
            ],
        ];

        if ($this->textGenerationConfig->getInstruction()) {
            $data['system_instruction'] = [
                'parts' => [['text' => $this->textGenerationConfig->getInstruction()->getMessage()]]
            ];
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function generateRequestUrl(): string
    {
        return sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $this->textGenerationConfig->getChatModel()->value,
            config('phpai.providers.gemini.api_key'),
        );
    }

    public function generate(): AITextGeneratorResponse
    {
        $response = Http::asJson()
            ->withBody($this->generateRequestData())
            ->post($this->generateRequestUrl());

        return new TextGenerationResponse($response);
    }
}