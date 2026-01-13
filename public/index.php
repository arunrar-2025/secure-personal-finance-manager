<?php
    require_once __DIR__ . '/../app/config/env.php';
    require_once __DIR__ . '/../app/config/database.php';
    require_once __DIR__ . '/../app/config/security.php';
    require_once __DIR__ . '/../app/models/User.php';
    require_once __DIR__ . '/../app/models/Account.php';
    require_once __DIR__ . '/../app/models/Transaction.php';
    require_once __DIR__ . '/../app/core/Encryption.php';
    require_once __DIR__ . '/../app/services/TransactionService.php';

    loadEnv(__DIR__ . '/../.env');