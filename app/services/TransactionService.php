<?php
    // app/services/TransactionService.php

    require_once __DIR__ . '/../models/Transaction.php';
    require_once __DIR__ . '/../core/Encryption.php';

    class TransactionService
    {
        private Transaction $transactionModel;
        private Encryption $encryption;

        public function __construct()
        {
            $this->transactionModel = new Transaction();
            $this->encryption = new Encryption();
        }

        public function addTransaction(int $accountId, string $category, string $amountPlain, string $note, string $transactionDate): bool
        {
            $amountEncrypted = $this->encryption->encrypt($amountPlain);

            return $this->transactionModel->create(
                $accountId,
                $category,
                $amountEncrypted,
                $note,
                $transactionDate
            );
        }

        public function getTransactionsByAccount(int $accountId): array
        {
            $rows = $this->transactionModel->findByAccount($accountId);

            foreach ($rows as &$row) {
                $row['amount'] = $this->encryption->decrypt($row['amount_encrypted']);
                unset($row['amount_encrypted']);
            }

            return $rows;
        }
    }