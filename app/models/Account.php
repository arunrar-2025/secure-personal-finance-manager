<?php
    // app/models/Account.php

    require_once __DIR__ . '/../config/database.php';

    class Account
    {
        private PDO $pdo;

        public function __construct()
        {
            $this->pdo = getPDO();
        }

        public function create(int $userId, string $name, string $type, string $balanceEncrypted): bool
        {
            $sql = "INSERT INTO accounts (user_id, name, type, balance_encrypted) VALUES (:user_id, :name, :type, :balance_encrypted";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':user_id' => $userId,
                ':name' => $name,
                ':type' => $type,
                ':balance_encrypted' => $balanceEncrypted
            ]);
        }

        public function findByUser(int $userId): array
        {
            $sql = "SELECT * FROM accounts WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId]);

            return $stmt->fetchAll();
        }

        public function findById(int $accountId): ?array
        {
            $sql = "SELECT * FROM accounts WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $accountId]);

            $account = $stmt->fetch();
            return $account ?: null;
        }

        public function updateBalance(int $accountId, string $balanceEncrypted): bool
        {
            $sql = "UPDATE accounts SET balance_encrypted = :balance WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':balance' => $balanceEncrypted,
                ':id' => $accountId
            ]);
        }
    }