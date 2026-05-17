<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ImportLog;
use App\Models\Product;

class ProductImportService
{
    public function handle(ImportLog $log, string $path): void
    {
        $fullPath = storage_path('app/private/' . $path);

        $handle = fopen($fullPath, 'r');

        if (!$handle) {
            throw new \Exception('Unable to open CSV file.');
        }

        $log->update([
            'status' => 'processing',
        ]);

        $errors = [];
        $importedCount = 0;
        $failedCount = 0;
        $rowNumber = 1;
        $batchSkus = [];

        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            $rowNumber++;

            $sku = trim($row[0] ?? '');
            $name = trim($row[1] ?? '');
            $price = $row[2] ?? null;
            $stock = $row[3] ?? null;
            $category = trim($row[4] ?? '');

            if (empty($sku)) {
                $failedCount++;

                $errors[] = [
                    'row' => $rowNumber,
                    'message' => 'SKU is required.',
                ];

                continue;
            }

            if (in_array($sku, $batchSkus) || Product::where('sku', $sku)->exists()) {

                $failedCount++;

                $errors[] = [
                    'row' => $rowNumber,
                    'message' => 'SKU already exists.',
                ];

                continue;
            }

            if (!is_numeric($price) || $price < 0) {

                $failedCount++;

                $errors[] = [
                    'row' => $rowNumber,
                    'message' => 'Price must be greater than or equal to 0.',
                ];

                continue;
            }

            if (!filter_var($stock, FILTER_VALIDATE_INT) || (int)$stock < 0) {

                $failedCount++;

                $errors[] = [
                    'row' => $rowNumber,
                    'message' => 'Stock must be valid integer.',
                ];

                continue;
            }

            Product::create([
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'stock' => (int)$stock,
                'category' => $category,
                'status' => 'active',
            ]);

            $batchSkus[] = $sku;

            $importedCount++;
        }

        fclose($handle);

        $log->update([
            'status' => 'completed',
            'imported_count' => $importedCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
        ]);
    }
}