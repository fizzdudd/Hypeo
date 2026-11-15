<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Groq (IA para generar campañas)
    |--------------------------------------------------------------------------
    | La API key vive solo en .env (nunca se sube a GitHub). El modelo es
    | configurable por si Groq lo retira del plan gratuito.
    */

    'groq' => [
        'key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'openai/gpt-oss-20b'),
        'url' => env('GROQ_URL', 'https://api.groq.com/openai/v1'),
        'timeout' => (int) env('GROQ_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Generación de imágenes (Pollinations, gratuito y sin API key)
    |--------------------------------------------------------------------------
    | Groq solo genera texto. La imagen se pide a Pollinations con el prompt
    | que escribe Groq; el navegador la carga directo desde la URL.
    */

    'imagenes' => [
        'activa' => (bool) env('IMAGEN_ACTIVA', true),
        'url' => env('IMAGEN_URL', 'https://image.pollinations.ai/prompt'),
    ],

];
