<?php

namespace R94ever\PHPAI\Tests\Unit;

use Mockery;
use R94ever\PHPAI\AI;
use R94ever\PHPAI\Services\Chatbot;
use R94ever\PHPAI\Tests\TestCase;

class AITest extends TestCase
{
    public function test_it_resolves_from_container()
    {
        $ai = $this->app->make(AI::class);
        $this->assertInstanceOf(AI::class, $ai);
    }

    public function test_it_uses_configured_default_provider()
    {
        config()->set('phpai.default_provider', 'gemini');
        $ai = $this->app->make(AI::class);
        $this->assertInstanceOf(Chatbot::class, $ai->chatbot());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
