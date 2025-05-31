# PHP AI Package

A simple and powerful AI integration package for PHP applications, providing seamless integration with various AI providers. Currently supports Google Gemini AI, with planned support for OpenAI and Anthropic Claude.

## Features

- 🤖 Multiple AI Provider Support
- 💬 Advanced Chat Capabilities
- 🔄 Conversation History Management
- ⚙️ Flexible Configuration System
- 🎯 Multiple Model Support
- 📊 Token Usage Tracking
- 🔍 Detailed Response Analysis
- 🛡️ Exception Handling & Error Management
- 📝 Event Logging System
- 🔒 Security First Approach
- 🧪 Comprehensive Testing Suite

## Requirements

- PHP 8.2 or higher
- Laravel 12.x or higher
- Composer
- Google Cloud Platform account with Gemini API access (for Gemini provider)

## Installation

You can install the package via composer:

```bash
composer require r94ever/phpai
```

## Configuration

### Laravel Integration

The package will automatically register itself if you're using Laravel. To publish the configuration file:

```bash
php artisan vendor:publish --provider="R94ever\PHPAI\AIServiceProvider"
```

This will create a `phpai.php` configuration file in your `config` directory:

```php
return [
    'chatbot' => [
        'providers' => [
            'gemini' => [
                'handler' => \R94ever\PHPAI\Providers\Gemini\GeminiProvider::class,
                'api_key' => env('CHATBOT_GEMINI_API_KEY', ''),
            ],
            // Add more providers here
        ],
    ]
];
```

Add the following environment variables to your `.env` file:

```env
CHATBOT_GEMINI_API_KEY=your-gemini-api-key
```

## Architecture

The package follows a clean, modular architecture:

### Core Components

- **AI Service**: Main entry point for interacting with AI capabilities
- **Provider Manager**: Manages and coordinates different AI providers 
- **Chatbot Service**: Handles chat interactions with AI providers

### Provider System

- **Provider Interface**: Standard interface for implementing AI providers
- **Configuration System**: Flexible provider-specific configurations
- **Response Handling**: Standardized response processing

### Event System

- Message Sent Events
- Response Received Events
- Error Events

## Basic Usage

```php
use R94ever\PHPAI\Facades\AI;

// Simple chat
$response = AI::chatbot()
    ->chat('Hello, how are you?');

// Chat with specific configuration
$response = AI::chatbot()
    ->withConfig(function ($config) {
        $config->setTemperature(0.7)
               ->setMaxOutputTokens(500);
    })
    ->chat('Write a poem about AI');

// Chat with system instruction
$response = AI::chatbot()
    ->withInstruction('You are a helpful SQL expert')
    ->chat('How to write a query to find duplicate records?');

// Chat with conversation history
use R94ever\PHPAI\Objects\ChatHistory;
use R94ever\PHPAI\Objects\ChatMessage;

$history = new ChatHistory([
    ChatMessage::user('What is your name?'),
    ChatMessage::assistant('My name is AI Assistant'),
]);

$response = AI::chatbot()
    ->withHistory($history)
    ->chat('Nice to meet you!');
```

## Advanced Usage

### Custom Models

When using the Gemini provider, you can choose from several available models:

```php
use R94ever\PHPAI\Providers\Gemini\ChatModel;

$response = AI::chatbot()
    ->useModel(ChatModel::Gemini2_5Flash)
    ->chat('Hello!');
```

Available models:

#### New Generation Models
- `Gemini2_5FlashPreview05_20`: Latest preview model with best price-performance ratio
- `Gemini2_5ProPreview`: Advanced model for complex reasoning tasks
- `Gemini2_5Flash`: High-speed next-gen features

#### Standard Models
- `Gemini2_0FlashLite`: Cost-efficient with low latency
- `Gemini1_5Flash`: Versatile multimodal model
- `Gemini1_5Flash8b`: Compact model for simple tasks
- `Gemini1_5Pro`: Balanced model for reasoning tasks
- `Gemini2_0FlashLive`: Real-time interaction model

### Response Handling

```php
$response = AI::chatbot()->chat('Hello');

// Get message content
$message = $response->getMessage();

// Success/Error checking
if ($response->isSuccess()) {
    // Handle success case
} else {
    $errorMessage = $response->getFailedMessage();
}

// Token usage metrics
$inputTokens = $response->getInputTokens();
$outputTokens = $response->getOutputTokens();
$totalTokens = $response->getTotalTokens();

// Additional metadata
$statusCode = $response->getStatusCode();
$modelVersion = $response->getModelVersion();
$responseId = $response->getResponseId();
```

### Event System

The package provides comprehensive event tracking:

```php
use R94ever\PHPAI\Events\ChatMessageSent;
use R94ever\PHPAI\Events\ChatResponseReceived;

// Track sent messages
Event::listen(function (ChatMessageSent $event) {
    Log::info('Message sent:', [
        'message' => $event->message,
        'user' => $event->userId
    ]);
});

// Track responses
Event::listen(function (ChatResponseReceived $event) {
    Log::info('Response received:', [
        'original' => $event->message,
        'response' => $event->response->getMessage(),
        'metrics' => [
            'tokens' => $event->response->getTotalTokens(),
            'model' => $event->response->getModelVersion()
        ]
    ]);
});
```

### Custom Providers

Implement your own AI providers:

```php
use R94ever\PHPAI\Contracts\ChatbotProvider;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;

class CustomProvider implements ChatbotProvider
{
    private AIGenerationConfig $config;

    public function getConfiguration(): AIGenerationConfig
    {
        return $this->config;
    }

    public function chat(ChatMessage $chatMessage): AITextGeneratorResponse
    {
        // Custom implementation
    }
}
```

Register your custom provider in your service provider's `boot` method:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use R94ever\PHPAI\Services\ChatbotProvidersManager;
use Your\Custom\Provider\CustomProvider;

class AIServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(ChatbotProvidersManager::class)
            ->register('custom', new CustomProvider());

        // You can also set it as the default provider
        $this->app->make(ChatbotProvidersManager::class)
            ->setDefault('custom');
    }
}

// Don't forget to register your service provider in config/app.php
'providers' => [
    // ...
    App\Providers\AIServiceProvider::class,
],
```

## Security

### API Key Protection

Always store API keys securely:

```php
// .env
CHATBOT_GEMINI_API_KEY=your-api-key

// config/phpai.php
'providers' => [
    'gemini' => [
        'api_key' => env('CHATBOT_GEMINI_API_KEY'),
    ],
]
```

### Rate Limiting

Implement rate limiting to protect your API usage:

```php
if (RateLimiter::tooManyAttempts('ai-chat:'.$userId, $perMinuteLimit)) {
    throw new ChatbotException('Rate limit exceeded');
}

RateLimiter::hit('ai-chat:'.$userId);
```

### Content Filtering

Add content filtering logic:

```php
Event::listen(function (ChatMessageSent $event) {
    if (containsSensitiveContent($event->message)) {
        throw new ChatbotException('Inappropriate content');
    }
});
```

## Testing

The package includes comprehensive tests:

```bash
composer test
```

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## Support

- Create issues on GitHub
- Email support: vandt147@gmail.com

## License

This package is open-source software licensed under the [MIT license](LICENSE).
