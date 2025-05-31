<?php

namespace R94ever\PHPAI\Services;

use InvalidArgumentException;
use R94ever\PHPAI\Contracts\ChatbotProvider;

class ChatbotProvidersManager
{
    private static array $registeredProviders = [];

    private ?string $defaultProviderName = null;

    public function __construct()
    {
        foreach (config('phpai.chatbot.providers', []) as $name => $configurations) {
            $providerClass = $configurations['handler'];
            $this->register($name, app($providerClass));
        }
        
        if ($defaultProvider = config('phpai.default_provider')) {
            try {
                $this->setDefault($defaultProvider);
            } catch (InvalidArgumentException $e) {
                // Silently fail if default provider is not registered
            }
        }
    }

    /**
     * Register a chatbot provider.
     *
     * @param string $name
     * @param ChatbotProvider $provider
     * @return self
     */
    public function register(string $name, ChatbotProvider $provider): self
    {
        self::$registeredProviders[$name] = $provider;

        return $this;
    }

    /**
     * Set the default chatbot provider by name.
     *
     * @param string $name
     * @return $this
     */
    public function setDefault(string $name): self
    {
        if ($this->has($name)) {
            $this->defaultProviderName = $name;
        } else {
            throw new InvalidArgumentException("Chatbot provider '$name' is not registered.");
        }

        return $this;
    }

    /**
     * Get the default chatbot provider.
     *
     * If only one provider is registered, it will be returned as the default.
     * If a specific default provider is set and exists, it will be returned.
     * Otherwise, null will be returned.
     *
     * @return ?ChatbotProvider
     */
    public function getDefault(): ?ChatbotProvider
    {
        if (count(self::$registeredProviders) === 1) {
            // If only one provider is registered, return it as the default
            return reset(self::$registeredProviders);
        }

        if ($this->defaultProviderName && $this->has($this->defaultProviderName)) {
            return $this->get($this->defaultProviderName);
        }

        return null;
    }

    /**
     * Unregister a chatbot provider by name.
     *
     * @param string $name
     * @return ?ChatbotProvider
     */
    public function get(string $name): ?ChatbotProvider
    {
        return self::$registeredProviders[$name] ?? null;
    }

    /**
     * Check if a provider is registered by name.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset(self::$registeredProviders[$name]);
    }

    public function isEmpty(): bool
    {
        return empty(self::$registeredProviders);
    }

    /**
     * Get all registered chatbot providers.
     *
     * @return array<string, ChatbotProvider>
     */
    public function all(): array
    {
        return self::$registeredProviders;
    }

    /**
     * Clear all registered chatbot providers.
     *
     * @return self
     */
    public function clear(): self
    {
        self::$registeredProviders = [];

        return $this;
    }
}