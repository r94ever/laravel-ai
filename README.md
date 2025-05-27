# PHP AI Package

A simple and powerful AI integration package for PHP applications, with built-in support for multiple AI providers including OpenAI, Anthropic, and Google Gemini.

## Installation

You can install the package via composer:

```bash
composer require r94ever/phpai
```

### Laravel Integration

The package will automatically register itself if you're using Laravel. If you need to publish the config file:

```bash
php artisan vendor:publish --provider="R94ever\PHPAI\PHPAIServiceProvider"
```

## Usage

### Basic Usage

```php
use R94ever\PHPAI\Facades\AI;

// Send a message and get response
$response = $chatbot = AI::chatbot()->chat('Hello, how are you?');
```

## Events

The package provides events that you can listen to:

- `ChatMessageSent`: Triggered when a message is sent to the AI provider
- `ChatResponseReceived`: Triggered when a response is received from the AI provider

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

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Author

- **VanDT147** - [vandt147@gmail.com](mailto:vandt147@gmail.com)

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
