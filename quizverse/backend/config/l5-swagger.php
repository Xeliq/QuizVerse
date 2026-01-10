<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'QuizVerse API',
                'description' => 'API dokumentacji dla aplikacji QuizVerse',
                'version' => '1.0.0',
            ],
            'routes' => [
                'api' => 'api.php',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_yaml' => storage_path('api-docs/swagger.yaml'),
                'docs' => storage_path('api-docs'),
                'docs_json' => 'api-docs.json',
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                ],
                'excludes' => [
                    base_path('app/Http/Controllers/Api/ProfileController.php'),
                ],
                'base' => env('L5_SWAGGER_BASE_PATH', '/api'),
            ],
            'security' => [
                'bearer' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'description' => 'Authorization token',
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => '/api/documentation',
            'docs_json' => '/api/documentation.json',
            'oauth2_callback' => 'oauth2-callback',
        ],
        'paths' => [
            'docs' => storage_path('api-docs'),
            'docs_json' => 'api-docs.json',
            'format_docs_with_yaml' => env('FORMAT_DOCS_WITH_YAML', false),
            'excludes' => [],
            'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
            'base' => env('L5_SWAGGER_BASE_PATH', '/api'),
        ],
        'scanOptions' => [
            'default' => [
                'pattern' => '*.php',
                'exclude' => ['Exceptions', 'Requests'],
            ],
        ],
    ],
    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
    'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
    'proxy' => false,
    'swagger_ui_enabled' => true,
    'models' => [
        'default' => true,
    ],
];


