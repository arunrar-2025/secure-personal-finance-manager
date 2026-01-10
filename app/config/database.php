<?php
    // app/config/database.php

    function getPDO(): PDO
    {
        static $pdo = null;

        if ($pdo !== null) {
            return $pdo;
        }

        $host = env('DB_HOST');
        $db = env('DB_NAME');
        $user = env('DB_USER');
        $pass = env('DB_PASS');

        if (!$host || !$db || !$user) {
            throw new RuntimeException('Database configuration is incomplete');
        }

        $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed');
        }

        return $pdo;
    }