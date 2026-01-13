<?php
    // app/core/Encryption.php

    require_once __DIR__ . '/../config/security.php';

    class Encryption
    {
        private string $key;
        private string $cipher = 'aes-256-cbc';

        public function __construct()
        {
            $this->key = getAppKey();
        }

        public function encrypt(string $plainText): string
        {
            $ivLength = openssl_cipher_iv_length($this->cipher);
            $iv = random_bytes($ivLength);

            $cipherText = openssl_encrypt(
                $plainText, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv
            );

            if ($cipherText === false) {
                throw new RuntimeException('Encryption failed');
            }

            // Store IV + Cipher text together
            return base64_encode($iv . $cipherText);
        }

        public function decrypt(string $encryptedData): string
        {
            $data = base64_decode($encryptedData);

            $ivLength = openssl_cipher_iv_length($this->cipher);
            $iv = substr($data, 0, $ivLength);
            $cipherText = substr($data, $ivLength);

            $plainText = openssl_decrypt(
                $cipherText,
                $this->cipher,
                $this->key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($plainText === false) {
                throw new RuntimeException('Decryption failed');
            }

            return $plainText;
        }
    }