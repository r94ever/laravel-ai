# PHP AI Package

A simple and powerful AI integration package for PHP applications, providing seamless integration with various AI providers. Currently supports Google Gemini AI, with planned support for OpenAI and Anthropic Claude.

## Requirements

- PHP 8.2 or higher
- Laravel 11.x or 12.x
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

This will create a `phpai.php` configuration file in your `config` directory. The configuration file includes:

```php
return [
    // Default AI provider to use
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'gemini'),

    // Provider-specific configurations
    'providers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY', ''),
        ],
    ],
];
```

Add the following environment variables to your `.env` file:

```env
AI_DEFAULT_PROVIDER=gemini
GEMINI_API_KEY=your-gemini-api-key
```

## Usage

### Basic Usage

```php
use R94ever\PHPAI\Facades\AI;

// Simple chat
$response = AI::chatbot()->chat('Hello, how are you?');
echo $response->getMessage();

// Chat with custom configuration
$response = AI::chatbot()
    ->withConfig(function ($config) {
        $config->setTemperature(0.7)
               ->setMaxOutputTokens(500)
               ->setTopP(0.8)
               ->setTopK(10);
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

### Available Models

When using the Gemini provider, you can choose from several available models:

```php
use R94ever\PHPAI\Providers\Gemini\ChatModel;

$response = AI::chatbot()
    ->useModel(ChatModel::Gemini2_5Flash)
    ->chat('Hello!');
```

Available models:
- `Gemini2_5FlashPreview05_20`: Best price-performance model with well-rounded capabilities
- `Gemini2_5ProPreview`: State-of-the-art thinking model for complex reasoning
- `Gemini2_5Flash`: Next-gen features with superior speed
- `Gemini2_0FlashLite`: Cost-efficient model with low latency
- `Gemini1_5Flash`: Fast and versatile multimodal model
- `Gemini1_5Flash8b`: Small model for simpler tasks
- `Gemini1_5Pro`: Mid-size model optimized for reasoning tasks
- `Gemini2_0FlashLive`: Real-time model for low-latency interactions

### Configuration Options

The following configuration options are available when using the chat API:

| Option | Description | Default | Range |
|--------|-------------|---------|--------|
| temperature | Controls randomness in responses | 1.0 | 0.0 - 1.0 |
| maxOutputTokens | Maximum length of generated text | 800 | > 0 |
| topP | Nucleus sampling threshold | 0.8 | 0.0 - 1.0 |
| topK | Top-k sampling threshold | 10 | ≥ 0 |

### Response Handling

The chat response object provides several methods for handling the AI's response:

```php
$response = AI::chatbot()->chat('Hello');

// Get the main response message
$message = $response->getMessage();

// Check if the request was successful
if ($response->isSuccess()) {
    // Handle success
}

// Get error messages if the request failed
if ($response->isFailed()) {
    $errorMessage = $response->getFailedMessage();
}

// Get token usage information
$inputTokens = $response->getInputTokens();
$outputTokens = $response->getOutputTokens();
$totalTokens = $response->getTotalTokens();

// Get response metadata
$statusCode = $response->getStatusCode();
$modelVersion = $response->getModelVersion();
$responseId = $response->getResponseId();
```

## Events

The package provides events that you can listen to for monitoring and logging purposes:

### ChatMessageSent

Triggered when a message is sent to the AI provider. Contains:
- `message`: The message text sent to the AI
- `userId`: Optional user identifier

```php
Event::listen(function (ChatMessageSent $event) {
    Log::info('Chat message sent:', [
        'message' => $event->message,
        'user' => $event->userId
    ]);
});
```

### ChatResponseReceived

Triggered when a response is received from the AI provider. Contains:
- `message`: The original message
- `response`: The AI response object
- `userId`: Optional user identifier

```php
Event::listen(function (ChatResponseReceived $event) {
    Log::info('Chat response received:', [
        'message' => $event->message,
        'response' => $event->response->getMessage(),
        'user' => $event->userId
    ]);
});
```

## Roadmap

### 1. Custom AI Provider Support
- Allow registration of custom AI providers
- Implement provider interface for easy integration
- Support for provider-specific configurations

### 2. AI Image Generation
- Integration with DALL-E, Stable Diffusion, and Midjourney
- Support for multiple image styles and sizes
- Image manipulation and editing capabilities

### 3. AI Video Generation
- Text-to-video generation
- Video editing and manipulation
- Support for multiple video formats and styles

### 4. Embeddings & Vector Stores
- Text embedding generation
- Integration with popular vector databases
- Semantic search capabilities
- Document similarity analysis
- Support for various vector store providers (Pinecone, Milvus, etc.)

## Security Considerations

### API Key Protection

Always store your API keys in environment variables or secure configuration storage. Never commit API keys to version control:

```php
// .env
GEMINI_API_KEY=your-key-here

// config/phpai.php
'providers' => [
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],
]
```

### Rate Limiting

Different Gemini models have different rate limits:

- Gemini 2.5 Flash Preview: More restricted rate limits (experimental)
- Gemini 2.5 Pro Preview: Limited rate limits during preview
- Gemini 2.0 Flash: Standard rate limits
- Gemini 1.5 Models: Standard rate limits

Implement appropriate rate limiting in your application to avoid hitting API limits:

```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

// Example rate limiting implementation
if (RateLimiter::tooManyAttempts('ai-chat:'.$userId, $perMinuteLimit)) {
    throw new ChatbotException('Too many requests. Please try again later.');
}

RateLimiter::hit('ai-chat:'.$userId);
```

### Content Filtering

The package respects Gemini's built-in content filtering. However, you may want to implement additional content filtering:

```php
use R94ever\PHPAI\Events\ChatMessageSent;

Event::listen(function (ChatMessageSent $event) {
    // Implement your content filtering logic
    if (containsSensitiveContent($event->message)) {
        throw new ChatbotException('Message contains inappropriate content');
    }
});
```

## Troubleshooting

### Common Issues

1. **API Key Issues**
```php
// Error: API key not valid
// Solution: Check your .env file and ensure the API key is correct
config(['phpai.providers.gemini.api_key' => 'your-new-key']);
```

2. **Rate Limiting**
```php
// Error: Rate limit exceeded
// Solution: Implement exponential backoff
try {
    $response = AI::chatbot()->chat($message);
} catch (Exception $e) {
    if (str_contains($e->getMessage(), 'rate limit')) {
        sleep(pow(2, $retryAttempt));
        // Retry the request
    }
}
```

3. **Model Availability**
```php
// Error: Model not available
// Solution: Fall back to an alternative model
try {
    $response = AI::chatbot()
        ->useModel(ChatModel::Gemini2_5Flash)
        ->chat($message);
} catch (Exception $e) {
    // Fall back to a more stable model
    $response = AI::chatbot()
        ->useModel(ChatModel::Gemini1_5Flash)
        ->chat($message);
}
```

### Debugging

Enable debug mode in your Laravel application to see detailed error messages:

```php
// config/app.php
'debug' => env('APP_DEBUG', true)
```

Use the events system for debugging:

```php
Event::listen(function (ChatMessageSent $event) {
    Log::debug('AI Chat Message:', [
        'message' => $event->message,
        'user' => $event->userId
    ]);
});

Event::listen(function (ChatResponseReceived $event) {
    Log::debug('AI Response:', [
        'status' => $event->response->getStatusCode(),
        'tokens' => $event->response->getTotalTokens(),
        'model' => $event->response->getModelVersion()
    ]);
});
```

## Versioning

See [CHANGELOG.md](CHANGELOG.md) for a full list of changes between versions.

## Advanced Usage

### Custom AI Providers

You can implement custom AI providers by implementing the `AIProvider` interface:

```php
use R94ever\PHPAI\Contracts\AIProvider;
use R94ever\PHPAI\Contracts\AIGenerationConfig;
use R94ever\PHPAI\Contracts\AITextGeneratorResponse;
use R94ever\PHPAI\Objects\ChatMessage;

class CustomProvider implements AIProvider
{
    private AIGenerationConfig $config;

    public function getConfiguration(): AIGenerationConfig
    {
        return $this->config;
    }

    public function chat(ChatMessage $chatMessage): AITextGeneratorResponse
    {
        // Implement your custom chat logic here
    }
}
```

### Error Handling

The package uses exceptions to handle errors:

```php
use R94ever\PHPAI\Exceptions\ChatbotException;

try {
    $response = AI::chatbot()->chat('');
} catch (ChatbotException $e) {
    // Handle empty message error
}

try {
    $config->setTopP(2.0);
} catch (ChatbotException $e) {
    // Handle invalid configuration error
}
```

### Testing

The package includes a comprehensive test suite. To run the tests:

```bash
composer test
```

## Contributing

We welcome contributions to improve the package! Please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run the tests (`composer test`)
5. Commit your changes (`git commit -m 'Add some amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Author

- **VanDT147** - [vandt147@gmail.com](mailto:vandt147@gmail.com)

## Support

If you discover any issues or have questions, please create an issue on GitHub.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
