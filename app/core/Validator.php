<?php
    // app/core/Validator.php

    class Validator
    {
        public static function email(string $email): bool
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }

        public static function amount(string $amount): bool
        {
            return preg_match('/^\d+(\.\d{1,2})?$/', $amount);
        }

        public static function date(string $date): bool
        {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            return $d && $d->format('Y-m-d') === $date;
        }

        public static function sanitizeString(string $value): string
        {
            return trim(strip_tags($value));
        }
    }