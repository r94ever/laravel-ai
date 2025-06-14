<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use Illuminate\Support\Facades\Http;
use Mockery;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
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
        
        $expected_url = 'https://generativelanguage.googleapis.com/v1beta/models/*';

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
    }

    public function test_it_get_chat_response_error_when_using_invalid_api_key()
    {
        $chatMessage = new ChatMessage('Hello');

        $expected_url = 'https://generativelanguage.googleapis.com/v1beta/models/*';

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

        $expected_url = 'https://generativelanguage.googleapis.com/v1beta/models/*';

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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
