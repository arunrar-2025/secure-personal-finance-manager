<?php
    // app/config/security.php

    // Password hashing configuration
    const PASSWORD_OPTIONS = [
        'cost' => 12
    ];

    // Session hardening configuration
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);

    // Load encryption key
    function getAppKey(): string
    {
        $key = env('APP_KEY');

        if (!$key) {
            throw new RuntimeException('APP_KEY is not set in environment');
        }

        // Key must be 32 bytes for AES-256
        if (strlen($key) !== 32) {
            throw new RuntimeException('APP_KEY must be exactly 32 characters');
        }

        return $key;
    }