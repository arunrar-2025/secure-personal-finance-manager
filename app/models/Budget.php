<?php
// app/models/Budget.php

require_once __DIR__ . '/../config/database.php';

class Budget
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function create(
        int $userId,
        string $category,
        string $limitEncrypted,
        string $monthYear
    ): bool {
        $sql = "INSERT INTO budgets (user_id, category, limit_amount_encrypted, month_year)
        VALUES (:user_id, :category, :limit_insert, :month_year)
        ON DUPLICATE KEY UPDATE limit_amount_encrypted = :limit_update";


        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_id' => $userId,
            ':category' => $category,
            ':limit_insert' => $limitEncrypted,
            ':limit_update' => $limitEncrypted,
            ':month_year' => $monthYear
        ]);
    }

    public function findByUserAndMonth(int $userId, string $monthYear): array
    {
        $sql = "SELECT * FROM budgets WHERE user_id = :user_id AND month_year = :month_year";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':month_year' => $monthYear
        ]);

        return $stmt->fetchAll();
    }
}
