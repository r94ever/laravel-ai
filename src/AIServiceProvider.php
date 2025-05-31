<?php

namespace R94ever\PHPAI;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use R94ever\PHPAI\Services\ChatbotProvidersManager;

class AIServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/phpai.php' => config_path('phpai.php')
        ], 'phpai');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ChatbotProvidersManager::class);

        $this->app->singleton(AI::class, function ($app) {
            return new AI($app->make(ChatbotProvidersManager::class));
        });

        $this->app->alias(AI::class, 'ai');
    }

    public function provides(): array
    {
        return [
            AI::class,
            ChatbotProvidersManager::class,
        ];
    }
}