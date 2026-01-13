<?php
    // app/services/ReportService.php

    require_once __DIR__ . '/../models/Transaction.php';
    require_once __DIR__ . '/../core/Encryption.php';

    class ReportService
    {
        private Transaction $transactionModel;
        private Encryption $encryption;

        public function __construct()
        {
            $this->transactionModel = new Transaction();
            $this->encryption = new Encryption();
        }

        public function getMonthlySummary(int $accountId, string $monthYear): array
        {
            $startDate = $monthYear . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));

            $rows = $this->transactionModel->findByDateRange($accountId, $startDate, $endDate);

            $totalIncome = 0.0;
            $totalExpense = 0.0;

            foreach ($rows as $row) {
                $amount = (float)$this->encryption->decrypt($row['amount_encrypted']);

                if ($amount >= 0) {
                    $totalIncome += $amount;
                } else {
                    $totalExpense += abs($amount);
                }
            }

            return [
                'month' => $monthYear,
                'income' => $totalIncome,
                'expense' => $totalExpense,
                'savings' => $totalIncome - $totalExpense
            ];
        }

        public function getCategoryBreakdown(int $accountId, string $monthYear): array
        {
            $startDate = $monthYear . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));

            $rows = $this->transactionModel->findByDateRange($accountId, $startDate, $endDate);

            $categories = [];

            foreach ($rows as $row) {
                $category = $row['category'];
                $amount = (float)$this->encryption->decrypt($row['amount_encrypted']);

                if (!isset($categories[$category])) {
                    $categories[$category] = 0.0;
                }

                $categories[$category] += abs($amount);
            }

            return $categories;
        }
    }