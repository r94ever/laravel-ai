<?php

return [
    /**
     * Configuration for the chatbot providers.
     * Each provider should have a 'handler' class and an 'api_key' if required.
     */
    'chatbot' => [
        /**
         * The providers array contains the configuration for each chatbot provider.
         * Each provider should have a unique name and its corresponding handler class.
         */
        'providers' => [
            /**
             * The 'gemini' provider configuration.
             * This provider uses the Gemini API for chatbot functionalities.
             */
            'gemini' => [
                /**
                 * The handler class for the Gemini provider.
                 * This class should implement the necessary methods to interact with the Gemini API.
                 */
                'handler' => \R94ever\PHPAI\Providers\Gemini\GeminiProvider::class,
                /**
                 * The API key for the Gemini provider.
                 * This key is used to authenticate requests to the Gemini API.
                 * It can be set in the .env file or directly here.
                 */
                'api_key' => env('CHATBOT_GEMINI_API_KEY', ''),
            ],
        ],
    ]
];
