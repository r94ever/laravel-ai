<?php

namespace R94ever\PHPAI\Tests;

use Illuminate\Support\Facades\Http;
use R94ever\PHPAI\AIServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [
            AIServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup PHPAI config
        $app['config']->set('phpai.chatbot', [
            'default_provider' => 'gemini',
            'providers' => [
                'gemini' => [
                    'handler' => \R94ever\PHPAI\Providers\Gemini\GeminiProvider::class,
                    'api_key' => 'test-key'
                ]
            ]
        ]);
        
        // Set default provider
        $app['config']->set('phpai.default_provider', 'gemini');
    }
}
