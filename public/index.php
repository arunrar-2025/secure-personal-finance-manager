<?php
    require_once __DIR__ . '/../app/config/env.php';

    loadEnv(__DIR__ . '/../.env');
    echo env('APP_NAME');