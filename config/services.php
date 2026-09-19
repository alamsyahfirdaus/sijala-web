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

    /*
    |--------------------------------------------------------------------------
    | Agora RTC
    |--------------------------------------------------------------------------
    |
    | Konfigurasi Agora digunakan untuk:
    | - Video Call
    | - Voice Call
    | - Real-Time Communication
    |
    | App ID digunakan oleh Flutter.
    | App Certificate hanya digunakan di Laravel untuk generate token.
    |
    */

    'agora' => [
        'app_id' => '9f4c2dde364c4787bf271f84a42b2171',
        'app_certificate' => '5abc242a7d274dcfad0b63e74f76e224',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp Gateway
    |--------------------------------------------------------------------------
    */
    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
        'url'   => env('FONNTE_URL', 'https://api.fonnte.com/send'),
    ],

];