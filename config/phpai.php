<?php

return [
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'gemini'),

    'providers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY', ''),
        ],
    ],
];
