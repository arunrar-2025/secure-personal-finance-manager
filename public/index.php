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

    case 'home':
    default:
        require __DIR__ . '/../app/views/home.php';
        break;
}
