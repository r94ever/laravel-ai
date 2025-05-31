<?php

namespace R94ever\PHPAI\Tests\Unit\Services;

use InvalidArgumentException;
use Mockery;
use R94ever\PHPAI\Contracts\ChatbotProvider;
use R94ever\PHPAI\Services\ChatbotProvidersManager;
use R94ever\PHPAI\Tests\TestCase;

class ChatbotProvidersManagerTest extends TestCase
{
    private ChatbotProvidersManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('phpai.chatbot.providers', []);
        $this->app['config']->set('phpai.default_provider');
        $this->manager = new ChatbotProvidersManager();
        $this->manager->clear();
    }

    public function test_it_can_register_provider()
    {
        $provider = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test', $provider);
        
        $this->assertTrue($this->manager->has('test'));
        $this->assertSame($provider, $this->manager->get('test'));
    }

    public function test_it_can_set_default_provider()
    {
        $provider = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test', $provider);
        $this->manager->setDefault('test');
        
        $this->assertSame($provider, $this->manager->getDefault());
    }

    public function test_it_throws_exception_when_setting_invalid_default_provider()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->manager->setDefault('non_existent');
    }

    public function test_it_returns_first_provider_as_default_when_only_one_exists()
    {
        $provider = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test', $provider);
        
        $this->assertSame($provider, $this->manager->getDefault());
    }

    public function test_it_returns_null_when_no_default_provider_set()
    {
        $provider1 = Mockery::mock(ChatbotProvider::class);
        $provider2 = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test1', $provider1);
        $this->manager->register('test2', $provider2);
        
        $this->assertNull($this->manager->getDefault());
    }

    public function test_it_can_get_all_providers()
    {
        // Clear existing providers from config
        $this->manager->clear();
        
        $provider1 = Mockery::mock(ChatbotProvider::class);
        $provider2 = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test1', $provider1);
        $this->manager->register('test2', $provider2);
        
        $providers = $this->manager->all();
        
        $this->assertCount(2, $providers);
        $this->assertSame($provider1, $providers['test1']);
        $this->assertSame($provider2, $providers['test2']);
    }

    public function test_it_can_clear_all_providers()
    {
        $provider = Mockery::mock(ChatbotProvider::class);
        
        $this->manager->register('test', $provider);
        $this->manager->clear();
        
        $this->assertTrue($this->manager->isEmpty());
        $this->assertCount(0, $this->manager->all());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
