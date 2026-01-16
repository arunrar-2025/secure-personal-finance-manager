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

function setFlash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

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
            setFlash("Invalid email or password.", "danger");
            header("Location: ?route=login");
            exit;
        }
        break;

    case 'dashboard':
        if (!$auth->check()) {
            setFlash("You must log in to access that page.", "danger");
            header("Location: ?route=login");
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
            setFlash("You must log in to access that page.", "danger");
            header("Location: ?route=login");
            exit;
        }
        
        require_once __DIR__ . '/../app/models/Account.php';
        require_once __DIR__ . '/../app/services/TransactionService.php';

        $userId = $auth->userId();

        $accountModel = new Account();
        $transactionService = new TransactionService();

        // Fetch accounts for dropdown
        $accounts = $accountModel->findByUser($userId);

        // Selected account
        $selectedAccountId = $_GET['account'] ?? ($accounts[0]['id'] ?? null);

        // Handle new transaction submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountId = (int)($_POST['account_id'] ?? 0);
            $category = trim($_POST['category'] ?? '');
            $amount = trim($_POST['amount'] ?? '');
            $note = trim($_POST['note'] ?? '');
            $date = $_POST['date'] ?? date('Y-m-d');

            if ($accountId && $category && $amount !== '') {
                $transactionService->addTransaction($accountId, $category, $amount, $note, $date);
            }

            header("Location: ?route=transactions&account=" . $accountId);
            exit;
        }

        // Fetch transactions for selected account
        $transactions = [];
        if ($selectedAccountId) {
            $transactions = $transactionService->getTransactionsByAccount((int)$selectedAccountId);
        }

        require __DIR__ . '/../app/views/transactions.php';
        break;

    case 'reports':
        if (!$auth->check()) {
            setFlash("You must log in to access that page.", "danger");
            header("Location: ?route=login");
            exit;
        }
        
        require_once __DIR__ . '/../app/services/ReportService.php';
        require_once __DIR__ . '/../app/models/Account.php';

        $userId = $auth->userId();
        $reportService = new ReportService();
        $accountModel = new Account();

        // Use first account for reports
        $accounts = $accountModel->findByUser($userId);
        if (empty($accounts)) {
            setFlash("Create at least one account before using this feature.", "warning");
            header("Location: ?route=accounts");
            exit;
        }

        $accountId = $accounts[0]['id'];

        // Month selector
        $monthYear = $_GET['month'] ?? date('Y-m');

        // Generate reports
        $summary = $reportService->getMonthlySummary($accountId, $monthYear);
        $categories = $reportService->getCategoryBreakdown($accountId, $monthYear);

        require __DIR__ . '/../app/views/reports.php';
        break;

    case 'accounts':
        if (!$auth->check()) {
            setFlash("You must log in to access that page.", "danger");
            header("Location: ?route=login");
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
            setFlash("You must log in to access that page.", "danger");
            header("Location: ?route=login");
            exit;
        }
        
        require_once __DIR__ . '/../app/models/Budget.php';
        require_once __DIR__ . '/../app/models/Account.php';
        require_once __DIR__ . '/../app/core/Encryption.php';
        require_once __DIR__ . '/../app/services/BudgetService.php';

        $userId = $auth->userId();

        $budgetModel = new Budget();
        $accountModel = new Account();
        $encryption = new Encryption();
        $budgetService = new BudgetService();

        // For simplicity, use first account for budget calculations
        $accounts = $accountModel->findByUser($userId);
        $accountId = $accounts[0]['id'] ?? null;

        $monthYear = $_GET['month'] ?? date('Y-m');

        // Handle new budget submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = trim($_POST['category'] ?? '');
            $limitPlain = trim($_POST['limit'] ?? '');

            if ($category && $limitPlain !== '') {
                $limitEncrypted = $encryption->encrypt($limitPlain);
                $budgetModel->create($userId, $category, $limitEncrypted, $monthYear);
            }

            header("Location: ?route=budgets&month=" . $monthYear);
            exit;
        }

        // Fetch budgets
        $budgetsRaw = $budgetModel->findByUserAndMonth($userId, $monthYear);
        $budgets = [];

        foreach ($budgetsRaw as $b) {
            $limitPlain = (float)$encryption->decrypt($b['limit_amount_encrypted']);
            $spent = 0.0;

            if ($accountId) {
                $spent = $budgetService->calculateMonthlyCategoryTotal($accountId, $b['category'], $monthYear);
            }

            $budgets[] = [
                'category' => $b['category'],
                'limit' => $limitPlain,
                'spent' => $spent,
                'over' => $spent > $limitPlain
            ];
        }

        require __DIR__ . '/../app/views/budgets.php';
        break;

    case 'register':
        require __DIR__ . '/../app/views/register.php';
        break;

    case 'register_submit':

        require_once __DIR__ . '/../app/models/User.php';

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$email || !$password || !$confirm) {
            setFlash("All fields are required.", "danger");
            header("Location: ?route=register");
            exit;
        }

        if ($password !== $confirm) {
            setFlash("Passwords do not match.", "danger");
            header("Location: ?route=register");
            exit;
        }

        $userModel = new User();

        // Check if email already exists
        if ($userModel->findByEmail($email)) {
            setFlash("Email already registered.", "warning");
            header("Location: ?route=register");
            exit;
        }

        // Create user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->create($email, $passwordHash);

        setFlash("Registration successful. You can log in now.", "success");
        header("Location: ?route=login");
        exit;

    case 'home':
    default:
        require __DIR__ . '/../app/views/home.php';
        break;
}
