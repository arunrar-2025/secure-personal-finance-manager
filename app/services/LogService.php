<?php
    // app/services/LogService.php

    class LogService
    {
        private string $logPath;

        public function __construct()
        {
            $this->logPath = __DIR__ . '/../../storage/logs/';

            if (!is_dir($this->logPath)) {
                mkdir($this->logPath, 0777, true);
            }
        }

        public function write(string $message): void
        {
            $date = date('Y-m-d H:i:s');
            $logLine = "[$date] " . $message . PHP_EOL;

            file_put_contents($this->logPath . 'app.log', $logLine, FILE_APPEND);
        }
    }