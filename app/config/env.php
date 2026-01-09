<?php

// app/config/env.php

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new RuntimeException('.env file not found');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));

        $_ENV[$key] = trim($value, "\"'");
    }
}

function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}