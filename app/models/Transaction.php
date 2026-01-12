<?php
    // app/models/Transaction.php

    require_once __DIR__ . '/../config/database.php';

    class Transaction
    {
        private PDO $pdo;

        public function __construct()
        {
            $this->pdo = getPDO();
        }

        public function create( int $accountId, string $category, string $amountEncrypted, string $note, string $transactionDate): bool
        {
            $sql = "INSERT INTO transactions (account_id, category, amount_encrypted, note, transaction_date) VALUES (:account_id, :category, :amount_encrypted, :note, :transaction_date)";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':account_id' => $accountId,
                ':category' => $category,
                ':amount_encrypted' => $amountEncrypted,
                ':note' => $note,
                ':transaction_date' => $transactionDate
            ]);
        }

        public function findByAccount(int $accountId): array
        {
            $sql = "SELECT * FROM transactions WHERE account_id = :account_id ORDER BY transaction_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':account_id' => $accountId]);

            return $stmt->fetchAll();
        }

        public function findByDateRange(int $accountId, string $startDate, string $endDate): array
        {
            $sql = "SELECT * FROM transactions WHERE account_id = :account_id AND transaction_date BETWEEN :start AND :end ORDER BY transaction_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':account_id' => $accountId,
                ':start' => $startDate,
                ':end' => $endDate
            ]);

            return $stmt->fetchAll();
        }

        public function delete(int $transactionId): bool
        {
            $sql = "DELETE FROM transactions WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([':id' => $transactionId]);
        }
    }