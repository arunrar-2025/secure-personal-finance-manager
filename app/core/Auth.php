<?php
    // app/core/Auth.php

    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../config/security.php';
    require_once __DIR__ . '/../services/LogService.php';

    class Auth
    {
        private User $userModel;
        private LogService $logger;

        public function __construct()
        {
            $this->userModel = new User();
            $this->logger = new LogService();
        }

        public function login(string $email, string $password): bool
        {
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                $this->logger->write("Failed login attempt for {$email}");
                return false;
            }

            if (!password_verify($password, $user['password_hash'])) {
                $this->logger->write("Failed login attempt for {$email}");
                return false;
            }

            // Session hardening
            session_regenerate_id(true);

            unset($_SESSION['csrf_token']);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_activity'] = time();

            $this->logger->write("User {$user['email']} logged in.");

            return true;
        }

        public function logout(): void
        {
            session_unset();
            $this->logger->write("User logged out.");
            session_destroy();
        }

        public function check(): bool
        {
            if(!isset($_SESSION['user_id'])) {
                return false;
            }

            // Session timeout: 30 minutes
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
                $this->logout();
                return false;
            }

            $_SESSION['last_activity'] = time();
            return true;
        }

        public function userId(): ?int
        {
            return $_SESSION['user_id'] ?? null;
        }

        public function generateCSRFToken(): string
        {
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            return $_SESSION['csrf_token'];
        }

        public function verifyCSRFToken(string $token): bool
        {
            if (!isset($_SESSION['csrf_token'])) {
                return false;
            }

            return hash_equals($_SESSION['csrf_token'], $token);
        }
    }