<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
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

    'piprapay' => [
        'base_url' => env('PIPRAPAY_BASE_URL', 'https://sandbox.piprapay.com'),
        'api_key' => env('PIPRAPAY_API_KEY'),
        'currency' => env('PIPRAPAY_CURRENCY', 'BDT'),
    ],

    'ga4' => [
        'gtm_id' => env('GTM_CONTAINER_ID'),           // GTM-XXXXXXX (client-side container)
        'measurement_id' => env('GA4_MEASUREMENT_ID'), // G-XXXXXXXXXX (GA4 web data stream)
        'api_secret' => env('GA4_API_SECRET'),         // Measurement Protocol secret (server-side)
        'currency' => env('GA4_CURRENCY', 'BDT'),
        'debug' => env('GA4_DEBUG', false),            // true => GA4 debug/validation endpoint
    ],

];
