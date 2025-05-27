<?php

namespace R94ever\PHPAI;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use R94ever\PHPAI\Enums\AIProvider;

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
        ], 'phpai-config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AI::class, function ($app) {
            $providerName = AIProvider::from(config('phpai.default_provider'));
            $provider = $providerName->createProvider();

            return new AI($provider);
        });

        $this->app->alias(AI::class, 'ai');
    }

    public function provides(): array
    {
        return [
            AI::class,
        ];
    }
}