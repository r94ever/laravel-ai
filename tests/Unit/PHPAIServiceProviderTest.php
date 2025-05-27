<?php

namespace R94ever\PHPAI\Tests\Unit;

use R94ever\PHPAI\AI;
use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Facades\AI as AIFacade;
use R94ever\PHPAI\PHPAIServiceProvider;
use R94ever\PHPAI\Tests\TestCase;

class PHPAIServiceProviderTest extends TestCase
{
    public function test_it_registers_singleton()
    {
        $this->assertTrue($this->app->bound(AI::class));
        $this->assertInstanceOf(AI::class, $this->app->make(AI::class));
    }

    public function test_it_registers_facade()
    {
        $this->assertInstanceOf(AI::class, AIFacade::getFacadeRoot());
    }

    public function test_it_registers_config()
    {
        $this->assertNotNull(config('phpai'));
        $this->assertIsString(config('phpai.default_provider'));
        $this->assertIsArray(config('phpai.providers'));
    }

    public function test_it_merges_config()
    {
        config()->set('phpai.custom_setting', 'test');
        $this->assertEquals('test', config('phpai.custom_setting'));
    }
}
