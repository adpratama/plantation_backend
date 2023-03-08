<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'auth/*',
        'email/*',
        'landing',
        'landing/*',
        'admin',
        'user',
        'admin/*',
        'user/*',
        'forgot-password',
        'reset-password',
        'export/*',
        'akun/*'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost', 'localhost:8000', 'http://localhost:3000', 'http://danaindonesiana.id', 'https://danaindonesiana.id', 'https://alpha.danaindonesiana.id', 'https://beta.danaindonesiana.id', 'http://fbk.id', 'https://fbk.id', 'http://beta.fbk.id', 'https://beta.fbk.id', 'https://danaindonesiana.kemdikbud.go.id', 'http://fbk.danaindonesiana.coba', 'https://precious-gumption-bfa0c1.netlify.app'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
