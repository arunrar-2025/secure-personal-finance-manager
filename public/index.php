<?php
// public/index.php

require_once __DIR__ . '/../app/config/env.php';

loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/config/security.php';

require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/services/TransactionService.php';
require_once __DIR__ . '/../app/services/ReportService.php';
require_once __DIR__ . '/../app/services/BudgetService.php';

session_start();

$auth = new Auth();

// Basic routing via ?route=
$route = $_GET['route'] ?? 'home';

switch ($route) {

    case 'login':
        require __DIR__ . '/../app/views/login.php';
        break;

    case 'login_submit':
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($auth->login($email, $password)) {
            header("Location: ?route=dashboard");
            exit;
        } else {
            echo "Login failed";
        }
        break;

    case 'dashboard':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }
        require __DIR__ . '/../app/views/dashboard.php';
        break;

    case 'logout':
        $auth->logout();
        header("Location: ?route=home");
        exit;

    case 'transactions':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }
        require __DIR__ . '/../app/views/transactions.php';
        break;

    case 'reports':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }
        require __DIR__ . '/../app/views/reports.php';
        break;

    case 'accounts':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }

        require_once __DIR__ . '/../app/models/Account.php';
        require_once __DIR__ . '/../app/core/Encryption.php';

        $accountModel = new Account();
        $encryption = new Encryption();
        $userId = $auth->userId();

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $type = $_POST['type'] ?? '';
            $balancePlain = trim($_POST['balance'] ?? '');

            if ($name && $type && $balancePlain !== '') {
                $balanceEncrypted = $encryption->encrypt($balancePlain);
                $accountModel->create($userId, $name, $type, $balanceEncrypted);
            }

            header("Location: ?route=accounts");
            exit;
        }

        // Fetch accounts
        $accountsRaw = $accountModel->findByUser($userId);
        $accounts = [];

        foreach ($accountsRaw as $acc) {
            $acc['balance'] = $encryption->decrypt($acc['balance_encrypted']);
            $accounts[] = $acc;
        }

        require __DIR__ . '/../app/views/accounts.php';
        break;

    case 'budgets':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }
        require __DIR__ . '/../app/views/budgets.php';
        break;

    case 'home':
    default:
        require __DIR__ . '/../app/views/home.php';
        break;
}
