<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleSheetProductImporter
{
    protected ?string $sheetUrl;

    public function __construct()
    {
        $this->sheetUrl = config('services.google_sheet_products_url');
    }

    public function import(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        if (empty($this->sheetUrl)) {
            Log::error('GoogleSheetImporter: GOOGLE_SHEET_PRODUCTS_URL is not set in .env');
            throw new \RuntimeException(
                'GOOGLE_SHEET_PRODUCTS_URL is missing from .env — add it and run: php artisan config:clear'
            );
        }

        try {
            $response = Http::timeout(30)->get($this->sheetUrl);

            if (! $response->successful()) {
                Log::error('GoogleSheetImporter: Failed to fetch sheet', [
                    'status' => $response->status(),
                    'url'    => $this->sheetUrl,
                ]);
                return $stats;
            }

            $rows = $this->parseCsv($response->body());

            if (empty($rows)) {
                Log::warning('GoogleSheetImporter: Sheet returned 0 rows — check columns match expected headers.');
                return $stats;
            }

            foreach ($rows as $index => $row) {
                try {
                    $result = $this->upsertProduct($row);
                    $stats[$result]++;
                } catch (\Exception $e) {
                    Log::warning("GoogleSheetImporter: Row {$index} error: " . $e->getMessage(), $row);
                    $stats['errors']++;
                }
            }

        } catch (\RuntimeException $e) {
            throw $e; // re-throw config errors so Artisan prints them
        } catch (\Exception $e) {
            Log::error('GoogleSheetImporter: Unexpected error — ' . $e->getMessage());
        }

        return $stats;
    }

    protected function parseCsv(string $csv): array
    {
        $rows    = [];
        $lines   = explode("\n", trim($csv));
        $headers = str_getcsv(array_shift($lines));

        // Trim headers in case sheet has spaces
        $headers = array_map('trim', $headers);

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }
            $values = str_getcsv($line);
            if (count($values) === count($headers)) {
                $rows[] = array_combine($headers, $values);
            }
        }

        return $rows;
    }

    protected function upsertProduct(array $row): string
    {
        if (empty(trim($row['name'] ?? ''))) {
            return 'skipped';
        }

        $externalId = trim($row['external_id'] ?? '');

        $data = [
            'name'           => trim($row['name']),
            'merchant'       => trim($row['merchant']       ?? ''),
            'network'        => trim($row['network']        ?? 'manual'),
            'brand'          => trim($row['brand']          ?? ''),
            'price'          => $this->parseDecimal($row['price']     ?? null),
            'old_price'      => $this->parseDecimal($row['old_price'] ?? null),
            'currency'       => trim($row['currency']       ?? 'RON'),
            'category'       => trim($row['category']       ?? ''),
            'image_url'      => trim($row['image_url']      ?? ''),
            'product_url'    => trim($row['product_url']    ?? ''),
            'affiliate_url'  => trim($row['affiliate_url']  ?? ''),
            'description'    => trim($row['description']    ?? ''),
            'is_active'      => filter_var($row['is_active'] ?? '1', FILTER_VALIDATE_BOOLEAN),
            'source'         => 'sheet',
            'last_synced_at' => now(),
        ];

        if (! empty($externalId)) {
            $existing = Product::where('external_id', $externalId)->first();

            if ($existing) {
                $existing->update($data);
                return 'updated';
            }

            $data['external_id'] = $externalId;
            $data['slug']        = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
            Product::create($data);
            return 'created';
        }

        // No external_id — match by name + merchant
        $existing = Product::where('name', $data['name'])
            ->where('merchant', $data['merchant'])
            ->first();

        if ($existing) {
            $existing->update($data);
            return 'updated';
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
        Product::create($data);
        return 'created';
    }

    protected function parseDecimal(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }
        // Handles Romanian format: 1.299,99 → 1299.99 and standard 1299.99
        $cleaned = preg_replace('/[^\d,\.]/', '', trim($value));
        $cleaned = str_replace(['.', ','], ['', '.'], $cleaned);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}
