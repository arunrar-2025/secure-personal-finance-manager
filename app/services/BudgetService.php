<?php
    // app/services/BudgetService.php

    require_once __DIR__ . '/../models/Transaction.php';
    require_once __DIR__ . '/../core/Encryption.php';

    class BudgetService
    {
        private Transaction $transactionModel;
        private Encryption $encryption;

        public function __construct()
        {
            $this->transactionModel = new Transaction();
            $this->encryption = new Encryption();
        }

        public function calculateMonthlyCategoryTotal(int $accountId, string $category, string $monthYear): float
        {
            $startDate = $monthYear . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));

            $rows = $this->transactionModel->findByDateRange($accountId, $startDate, $endDate);

            $total = 0.0;

            foreach ($rows as $row) {
                if ($row['category'] !== $category){
                    continue;
                }

                $amountPlain = $this->encryption->decrypt($row['amount_encrypted']);
                $total += (float)$amountPlain;
            }

            return $total;
        }

        public function isOverBudget(int $accountId, string $category, string $monthYear, float $budgetLimitPlain): bool
        {
            $spent = $this->calculateMonthlyCategoryTotal($accountId, $category, $monthYear);

            return $spent > $budgetLimitPlain;
        }
    }