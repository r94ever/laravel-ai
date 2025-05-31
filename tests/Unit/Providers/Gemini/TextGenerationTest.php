<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use Illuminate\Support\Facades\Http;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Providers\Gemini\ChatModel;
use R94ever\PHPAI\Providers\Gemini\Configs\GenerationConfig;
use R94ever\PHPAI\Providers\Gemini\TextGeneration;
use R94ever\PHPAI\Tests\TestCase;

class TextGenerationTest extends TestCase
{
    private TextGeneration $textGeneration;
    private GenerationConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->config = new GenerationConfig();
        $this->config->setChatModel(ChatModel::Gemini2_5Flash);
        
        $this->textGeneration = new TextGeneration(
            new ChatMessage('Test message'),
            $this->config
        );
    }

    public function test_it_generates_request_url_correctly()
    {
        $expected = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            ChatModel::Gemini2_5Flash->value
        );

        Http::fake([
            '*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'Test response']]]]]])
        ]);

        $this->textGeneration->generate();

        Http::assertSent(function ($request) use ($expected) {
            return str_starts_with($request->url(), $expected);
        });
    }

    public function test_it_includes_api_key_in_request()
    {
        Http::fake();
        
        $this->textGeneration->generate();
        
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'key=test-key');
        });
    }

    public function test_it_includes_chat_history_in_request()
    {
        $history = new ChatHistory([
            new ChatMessage('Previous message'),
            new ChatMessage('Previous response', ChatMessage::ROLE_ASSISTANT)
        ]);
        
        $this->config->setChatHistory($history);
        
        Http::fake();
        
        $this->textGeneration->generate();
        
        Http::assertSent(function ($request) {
            $data = json_decode($request->body(), true);
            return count($data['contents']) === 3; // 2 history messages + 1 current message
        });
    }

    public function test_it_includes_instruction_in_request()
    {
        $instruction = 'Test instruction';
        $this->config->setInstruction(ChatMessage::instructor($instruction));
        
        Http::fake();
        
        $this->textGeneration->generate();
        
        Http::assertSent(function ($request) use ($instruction) {
            $data = json_decode($request->body(), true);
            return isset($data['system_instruction']) 
                && $data['system_instruction']['parts'][0]['text'] === $instruction;
        });
    }

    public function test_it_includes_generation_config_in_request()
    {
        $this->config->setTemperature(0.7)
            ->setMaxOutputTokens(1000)
            ->setTopP(0.9)
            ->setTopK(20);
        
        Http::fake();
        
        $this->textGeneration->generate();
        
        Http::assertSent(function ($request) {
            $data = json_decode($request->body(), true);
            return $data['generationConfig']['temperature'] === 0.7
                && $data['generationConfig']['maxOutputTokens'] === 1000
                && $data['generationConfig']['topP'] === 0.9
                && $data['generationConfig']['topK'] === 20;
        });
    }

    public function test_it_maps_roles_correctly()
    {
        $history = new ChatHistory([
            new ChatMessage('User message', ChatMessage::ROLE_USER),
            new ChatMessage('Assistant message', ChatMessage::ROLE_ASSISTANT),
            new ChatMessage('Instructor message', ChatMessage::ROLE_INSTRUCTOR)
        ]);
        
        $this->config->setChatHistory($history);
        
        Http::fake();
        
        $this->textGeneration->generate();
        
        Http::assertSent(function ($request) {
            $data = json_decode($request->body(), true);
            return $data['contents'][0]['role'] === 'user'
                && $data['contents'][1]['role'] === 'model'
                && $data['contents'][2]['role'] === 'assistant';
        });
    }
}
