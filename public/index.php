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

    var_dump(Validator::email("test@example.com"));
    var_dump(Validator::amount("123.45"));
    var_dump(Validator::date("2026-01-10"));