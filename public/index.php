<?php
    require_once __DIR__ . '/../app/config/env.php';
    require_once __DIR__ . '/../app/config/database.php';
    require_once __DIR__ . '/../app/config/security.php';
    require_once __DIR__ . '/../app/models/User.php';
    require_once __DIR__ . '/../app/models/Account.php';
    require_once __DIR__ . '/../app/models/Transaction.php';
    require_once __DIR__ . '/../app/core/Encryption.php';
    require_once __DIR__ . '/../app/services/TransactionService.php';
    require_once __DIR__ . '/../app/core/Validator.php';

    loadEnv(__DIR__ . '/../.env');

    session_start();
    require_once __DIR__ . '/../app/core/Auth.php';
    require_once __DIR__ . '/../app/services/BudgetService.php';
    require_once __DIR__ . '/../app/services/ReportService.php';

    