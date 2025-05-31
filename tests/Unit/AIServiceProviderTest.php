<?php

namespace R94ever\PHPAI\Tests\Unit;

use R94ever\PHPAI\AI;
use R94ever\PHPAI\AIServiceProvider;
use R94ever\PHPAI\Facades\AI as AIFacade;
use R94ever\PHPAI\Services\ChatbotProvidersManager;
use R94ever\PHPAI\Tests\TestCase;

class AIServiceProviderTest extends TestCase
{
    public function test_it_registers_singleton()
    {
        $this->assertTrue($this->app->bound(AI::class));
        $this->assertInstanceOf(AI::class, $this->app->make(AI::class));
    }

    public function test_it_registers_providers_manager_singleton()
    {
        $this->assertTrue($this->app->bound(ChatbotProvidersManager::class));
        $this->assertInstanceOf(
            ChatbotProvidersManager::class,
            $this->app->make(ChatbotProvidersManager::class)
        );
    }

    public function test_it_registers_facade()
    {
        $this->assertInstanceOf(AI::class, AIFacade::getFacadeRoot());
    }

    public function test_it_registers_config()
    {
        $this->assertNotNull(config('phpai'));
        $this->assertArrayHasKey('providers', config('phpai.chatbot'));
        $this->assertArrayHasKey('gemini', config('phpai.chatbot.providers'));
    }

    public function test_it_registers_config_with_correct_structure()
    {
        $config = config('phpai.chatbot.providers.gemini');
        
        $this->assertArrayHasKey('handler', $config);
        $this->assertArrayHasKey('api_key', $config);
    }

    public function test_it_provides_correct_services()
    {
        $provider = new AIServiceProvider($this->app);
        
        $provided = $provider->provides();
        
        $this->assertContains(AI::class, $provided);
        $this->assertContains(ChatbotProvidersManager::class, $provided);
    }    public function test_it_publishes_config_file()
    {
        $provider = new AIServiceProvider($this->app);
        
        $this->assertFileExists(__DIR__ . '/../../config/phpai.php', 'Config source file missing');
        
        $provider->boot();
        
        // Verify that the provider correctly registers paths for publishing
        $paths = $provider->pathsToPublish();
        $this->assertNotNull($paths, 'No paths registered for publishing');
        $this->assertNotEmpty($paths, 'No paths registered for publishing');
        
        $configSourcePath = realpath(__DIR__ . '/../../config/phpai.php');
        $foundPath = false;
        
        foreach ($paths as $path => $target) {
            if (realpath($path) === $configSourcePath) {
                $foundPath = true;
                $this->assertStringEndsWith('phpai.php', $target, 'Invalid publish target');
                break;
            }
        }
        
        $this->assertTrue($foundPath, 'Config file not registered for publishing');
    }
}
