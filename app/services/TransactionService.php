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
            // Encrypt and store transaction
            $amountEncrypted = $this->encryption->encrypt($amountPlain);

            $success = $this->transactionModel->create(
                $accountId,
                $category,
                $amountEncrypted,
                $note,
                $transactionDate
            );

            if (!$success) {
                return false;
            }

            // --- Update account balance ---
            require_once __DIR__ . '/../models/Account.php';
            $accountModel = new Account();

            $account = $accountModel->findById($accountId);
            if (!$account) {
                return false;
            }

            // Decrypt current balance
            $currentBalance = (float)$this->encryption->decrypt($account['balance_encrypted']);

            // Apply transaction amount
            $newBalance = $currentBalance + (float)$amountPlain;

            // Encrypt new balance
            $newBalanceEncrypted = $this->encryption->encrypt((string)$newBalance);

            // Update account balance in database
            return $accountModel->updateBalance($accountId, $newBalanceEncrypted);

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