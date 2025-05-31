<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use Illuminate\Http\Client\Response;
use Mockery;
use R94ever\PHPAI\Providers\Gemini\TextGenerationResponse;
use R94ever\PHPAI\Tests\TestCase;

class TextGenerationResponseTest extends TestCase
{
    private Response $httpResponse;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpResponse = Mockery::mock(Response::class);
    }

    public function test_it_gets_message_from_response()
    {
        $this->httpResponse->shouldReceive('json')
            ->with('candidates.0.content.parts.0.text', 'No response from AI service')
            ->andReturn('Test response');

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertEquals('Test response', $response->getMessage());
    }

    public function test_it_gets_failed_message_from_response()
    {
        $this->httpResponse->shouldReceive('json')
            ->with('error.message', 'No error message provided')
            ->andReturn('Test error message');

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertEquals('Test error message', $response->getFailedMessage());
    }

    public function test_it_gets_status_code()
    {
        $this->httpResponse->shouldReceive('status')
            ->andReturn(200);

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_gets_token_counts()
    {
        $this->httpResponse->shouldReceive('json')
            ->with('usageMetadata.promptTokenCount', 0)
            ->andReturn(10);

        $this->httpResponse->shouldReceive('json')
            ->with('usageMetadata.candidatesTokenCount', 0)
            ->andReturn(5);

        $this->httpResponse->shouldReceive('json')
            ->with('usageMetadata.totalTokenCount', 0)
            ->andReturn(15);

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertEquals(10, $response->getInputTokens());
        $this->assertEquals(5, $response->getOutputTokens());
        $this->assertEquals(15, $response->getTotalTokens());
    }

    public function test_it_gets_response_metadata()
    {
        $this->httpResponse->shouldReceive('json')
            ->with('responseId', '')
            ->andReturn('test-response-id');

        $this->httpResponse->shouldReceive('json')
            ->with('modelVersion', '')
            ->andReturn('test-model-version');

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertEquals('test-response-id', $response->getResponseId());
        $this->assertEquals('test-model-version', $response->getModelVersion());
    }

    public function test_it_detects_success_and_failure()
    {
        $this->httpResponse->shouldReceive('failed')->andReturn(false);
        $this->httpResponse->shouldReceive('successful')->andReturn(true);

        $response = new TextGenerationResponse($this->httpResponse);

        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isFailed());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
