<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use Illuminate\Support\Facades\Http;
use Mockery;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;
use R94ever\PHPAI\Providers\Gemini\ChatModel;
use R94ever\PHPAI\Providers\Gemini\GeminiProvider;
use R94ever\PHPAI\Tests\TestCase;

class GeminiProviderTest extends TestCase
{
    private GeminiProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = new GeminiProvider();

        $config = $this->provider->getConfiguration();
        $config->setChatModel(ChatModel::Gemini2_5Flash);
    }

    public function test_it_gets_default_configuration()
    {
        $config = $this->provider->getConfiguration();

        $this->assertInstanceOf(AIGenerationConfig::class, $config);
        $this->assertInstanceOf(ChatModel::class, $config->getChatModel());
    }

    public function test_it_get_chat_response_successful()
    {
        $chatMessage = new ChatMessage('Hello');
        
        $expected_url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            ChatModel::Gemini2_5Flash->value,
            'test-key'
        );

        Http::fake([
            $expected_url => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Mocked AI response']
                            ]
                        ]
                    ]
                ],
                'usageMetadata' => [
                    'promptTokenCount' => 10,
                    'candidatesTokenCount' => 5,
                    'totalTokenCount' => 15
                ],
                'modelVersion' => 'gemini-2.5-flash-8.00',
                'responseId' => '12345'
            ])
        ]);

        $response = $this->provider->chat($chatMessage);
        
        $this->assertInstanceOf(AITextGeneratorResponse::class, $response);
        $this->assertEquals('Mocked AI response', $response->getMessage());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(10, $response->getInputTokens());
        $this->assertEquals(5, $response->getOutputTokens());
        $this->assertEquals(15, $response->getTotalTokens());
        $this->assertEquals('gemini-2.5-flash-8.00', $response->getModelVersion());
        $this->assertEquals('12345', $response->getResponseId());

        Http::assertSent(function ($request) use ($expected_url) {
            return $request->url() === $expected_url
                && $request->method() === 'POST'
                && isset($request->data()['contents']);
        });
    }

    public function test_it_get_chat_response_error_when_using_invalid_api_key()
    {
        $chatMessage = new ChatMessage('Hello');

        $expected_url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            ChatModel::Gemini2_5Flash->value,
            'test-key'
        );

        Http::fake([
            $expected_url => Http::response([
                'error' => [
                    'code' => 400,
                    'message' => 'API key not valid. Please pass a valid API key.',
                    'status' => 'INVALID_ARGUMENT'
                ]
            ], 400)
        ]);

        $response = $this->provider->chat($chatMessage);

        $this->assertInstanceOf(AITextGeneratorResponse::class, $response);
        $this->assertEquals('No response from AI service', $response->getMessage());
        $this->assertEquals('API key not valid. Please pass a valid API key.', $response->getFailedMessage());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(0, $response->getInputTokens());
        $this->assertEquals(0, $response->getOutputTokens());
        $this->assertEquals(0, $response->getTotalTokens());
        $this->assertEquals('', $response->getModelVersion());
        $this->assertEquals('', $response->getResponseId());
    }

    public function test_it_get_chat_response_error_when_using_invalid_model()
    {
        $chatMessage = new ChatMessage('Hello');

        $expected_url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            ChatModel::Gemini2_5Flash->value,
            'test-key'
        );

        Http::fake([
            $expected_url => Http::response([
                'error' => [
                    'code' => 404,
                    'message' => 'models/abc123 is not found for API version v1beta, or is not supported for generateContent. Call ListModels to see the list of available models and their supported methods.',
                    'status' => 'INVALID_ARGUMENT'
                ]
            ], 404)
        ]);

        $response = $this->provider->chat($chatMessage);

        $this->assertInstanceOf(AITextGeneratorResponse::class, $response);
        $this->assertEquals('No response from AI service', $response->getMessage());
        $this->assertEquals('models/abc123 is not found for API version v1beta, or is not supported for generateContent. Call ListModels to see the list of available models and their supported methods.', $response->getFailedMessage());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(0, $response->getInputTokens());
        $this->assertEquals(0, $response->getOutputTokens());
        $this->assertEquals(0, $response->getTotalTokens());
        $this->assertEquals('', $response->getModelVersion());
        $this->assertEquals('', $response->getResponseId());
    }

    public function test_it_includes_chat_history_in_request()
    {
        $history = new ChatHistory([
            new ChatMessage('What is your name?'),
            new ChatMessage('My name is AI Assistant', ChatMessage::ROLE_ASSISTANT),
            new ChatMessage('How old are you?')
        ]);
        
        $this->provider->getConfiguration()->setChatHistory($history);
        $chatMessage = new ChatMessage('Nice to meet you!');

        $expected_url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            ChatModel::Gemini2_5Flash->value,
            'test-key'
        );

        Http::fake([
            $expected_url => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Nice to meet you too!']
                            ]
                        ]
                    ]
                ]
            ])
        ]);

        $this->provider->chat($chatMessage);

        Http::assertSent(function ($request) use ($expected_url) {
            $data = json_decode($request->body(), true);
            
            return $request->url() === $expected_url
                && $request->method() === 'POST'
                && isset($data['contents'])
                && count($data['contents']) === 4 // 3 history messages + 1 new message
                && $data['contents'][0]['parts'][0]['text'] === 'What is your name?'
                && $data['contents'][1]['parts'][0]['text'] === 'My name is AI Assistant'
                && $data['contents'][2]['parts'][0]['text'] === 'How old are you?'
                && $data['contents'][3]['parts'][0]['text'] === 'Nice to meet you!';
        });
    }



    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
