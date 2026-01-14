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
        // placeholder response
        echo "Login endpoint";
        break;

    case 'dashboard':
        if (!$auth->check()) {
            echo "Unauthorized";
            exit;
        }
        echo "Dashboard endpoint";
        break;

    case 'logout':
        $auth->logout();
        echo "Logged out";
        break;

    default:
        echo "Home endpoint";
        break;
}
