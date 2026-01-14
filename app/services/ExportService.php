<?php
    // app/services/ExportService.php

    class ExportService
    {
        private string $exportPath;

        public function __construct()
        {
            $this->exportPath = __DIR__ . '/../../storage/exports/';

            if (!is_dir($this->exportPath)) {
                mkdir($this->exportPath, 0777, true);
            }
        }

        public function exportToCSV(array $data, string $filenamePrefix): string
        {
            $filename = $filenamePrefix . '_' . time() . '.csv';
            $filePath = $this->exportPath . $filename;

            $file = fopen($filePath, 'w');

            // Write header
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
            }

            // Write data rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);

            return $filePath;
        }

        public function exportToPDF(array $summary, string $filenamePrefix): string
        {
            // Simple text-based PDF export for demonstration purposes
            $filename = $filenamePrefix . '_' . time() . '.pdf';
            $filePath = $this->exportPath . $filename;

            $content = "Financial Report\n\n";
            foreach ($summary as $key => $value) {
                $content .= ucfirst($key) . ": " . $value . "\n";
            }

            // Minimal PDF wrapper
            $pdfContent = "%PDF-1.1\n";
            $pdfContent .= "1 0 obj<<>>endobj\n";
            $pdfContent .= "2 0 obj<< /Length " . strlen($content) . " >>stream\n";
            $pdfContent .= $content . "\nendstream\nendobj\n";
            $pdfContent .= "3 0 obj<< /Type /Page /Parent 4 0 R /Contents 2 0 R>>endobj\n";
            $pdfContent .= "4 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1>>endobj\n";
            $pdfContent .= "5 0 obj<< /Type /Catalog /Pages 4 0 R>>endobj\n";
            $pdfContent .= "xref\n0 6\n0000000000 65535 f \n";
            $pdfContent .= "trailer<< /Root 5 0 R>>\nstartxref\n";
            $pdfContent .= strlen($pdfContent) . "\n%%EOF";

            file_put_contents($filePath, $pdfContent);

            return $filePath;
        }
    }