<?php

namespace R94ever\PHPAI\Tests;

use Illuminate\Support\Facades\Http;
use R94ever\PHPAI\PHPAIServiceProvider;

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
            PHPAIServiceProvider::class,
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
        $app['config']->set('phpai.default_provider', 'gemini');
        $app['config']->set('phpai.providers.gemini.api_key', 'test-key');
    }
}
