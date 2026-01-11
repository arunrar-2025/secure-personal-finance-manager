<?php
    // app/models/User.php

    require_once __DIR__ . '/../config/database.php';

    class User
    {
        private PDO $pdo;

        public function __construct()
        {
            $this->pdo = getPDO();
        }

        public function create(string $email, string $passwordHash): bool
        {
            $sql = "INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':email' => $email,
                ':password_hash' => $passwordHash
            ]);
        }

        public function findByEmail(string $email): ?array
        {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);

            $user = $stmt->fetch();
            return $user ?: null;
        }

        public function findById(int $id): ?array
        {
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            $user = $stmt->fetch();
            return $user ?: null;
        }
    }