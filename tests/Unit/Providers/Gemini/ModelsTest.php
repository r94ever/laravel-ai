<?php

namespace R94ever\PHPAI\Tests\Unit\Providers\Gemini;

use R94ever\PHPAI\Providers\Gemini\ChatModel;
use R94ever\PHPAI\Providers\Gemini\EmbeddingModel;
use R94ever\PHPAI\Tests\TestCase;

class ModelsTest extends TestCase
{
    public function test_chat_model_has_correct_values()
    {
        $this->assertEquals('gemini-2.5-flash-preview-05-20', ChatModel::Gemini2_5FlashPreview05_20->value);
        $this->assertEquals('gemini-2.5-pro-preview-05-06', ChatModel::Gemini2_5ProPreview->value);
        $this->assertEquals('gemini-2.0-flash', ChatModel::Gemini2_5Flash->value);
        $this->assertEquals('gemini-2.0-flash-lite', ChatModel::Gemini2_0FlashLite->value);
        $this->assertEquals('gemini-1.5-flash', ChatModel::Gemini1_5Flash->value);
        $this->assertEquals('gemini-1.5-flash-8b', ChatModel::Gemini1_5Flash8b->value);
        $this->assertEquals('gemini-1.5-pro', ChatModel::Gemini1_5Pro->value);
        $this->assertEquals('gemini-2.0-flash-live-001', ChatModel::Gemini2_0FlashLive->value);
    }

    public function test_embedding_model_has_correct_values()
    {
        $this->assertEquals('gemini-embedding-exp-03-07', EmbeddingModel::GeminiEmbeddingExp03_07->value);
        $this->assertEquals('text-embedding-004', EmbeddingModel::TextEmbedding004->value);
        $this->assertEquals('embedding-001', EmbeddingModel::Embedding001->value);
    }

    public function test_chat_model_cases_are_valid()
    {
        foreach (ChatModel::cases() as $case) {
            $this->assertIsString($case->value);
            $this->assertNotEmpty($case->value);
        }
    }

    public function test_embedding_model_cases_are_valid()
    {
        foreach (EmbeddingModel::cases() as $case) {
            $this->assertIsString($case->value);
            $this->assertNotEmpty($case->value);
        }
    }
}
