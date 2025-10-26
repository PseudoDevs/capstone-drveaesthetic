<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | application to enhance protection against various attacks.
    |
    */

    'rate_limits' => [
        'auth' => [
            'login' => 10,      // 10 attempts per minute
            'register' => 5,    // 5 attempts per minute
            'password_reset' => 3, // 3 attempts per minute
        ],
        'api' => [
            'general' => 100,    // 100 requests per minute
            'upload' => 20,      // 20 uploads per minute
            'chat' => 60,        // 60 messages per minute
        ],
    ],

    'request_limits' => [
        'max_size' => 1048576,      // 1MB for general requests
        'auth_max_size' => 512000,  // 512KB for auth requests
        'upload_max_size' => 2097152, // 2MB for file uploads
    ],

    'session' => [
        'encrypt' => true,
        'secure_cookies' => true,
        'http_only' => true,
        'same_site' => 'strict',
        'lifetime' => 120, // 2 hours
    ],

    'headers' => [
        'x_content_type_options' => 'nosniff',
        'x_frame_options' => 'DENY',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
    ],

    'validation' => [
        'password_min_length' => 8,
        'password_require_confirmation' => true,
        'email_verification_required' => false, // Set to true in production
        'phone_verification_required' => false,  // Set to true in production
    ],

    'logging' => [
        'security_events' => true,
        'failed_logins' => true,
        'suspicious_activity' => true,
        'debug_in_production' => false,
    ],
];
