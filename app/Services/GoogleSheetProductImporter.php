<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleSheetProductImporter
{
    protected string $sheetUrl;

    public function __construct()
    {
        $this->sheetUrl = config('services.google_sheet_products_url');
    }

    public function import(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        try {
            $response = Http::timeout(30)->get($this->sheetUrl);

            if (!$response->successful()) {
                Log::error('GoogleSheetImporter: Failed to fetch sheet', [
                    'status' => $response->status(),
                    'url'    => $this->sheetUrl,
                ]);
                return $stats;
            }

            $rows = $this->parseCsv($response->body());

            foreach ($rows as $index => $row) {
                try {
                    $result = $this->upsertProduct($row);
                    $stats[$result]++;
                } catch (\Exception $e) {
                    Log::warning("GoogleSheetImporter: Row {$index} error: " . $e->getMessage(), $row);
                    $stats['errors']++;
                }
            }

        } catch (\Exception $e) {
            Log::error('GoogleSheetImporter: ' . $e->getMessage());
        }

        return $stats;
    }

    protected function parseCsv(string $csv): array
    {
        $rows = [];
        $lines = explode("\n", trim($csv));
        $headers = str_getcsv(array_shift($lines));

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            $values = str_getcsv($line);
            if (count($values) === count($headers)) {
                $rows[] = array_combine($headers, $values);
            }
        }

        return $rows;
    }

    protected function upsertProduct(array $row): string
    {
        // Skip rows with no name
        if (empty($row['name'])) {
            return 'skipped';
        }

        $externalId = $row['external_id'] ?? null;

        $data = [
            'name'          => trim($row['name']),
            'merchant'      => trim($row['merchant'] ?? ''),
            'network'       => trim($row['network'] ?? 'manual'),
            'brand'         => trim($row['brand'] ?? ''),
            'price'         => $this->parseDecimal($row['price'] ?? null),
            'old_price'     => $this->parseDecimal($row['old_price'] ?? null),
            'currency'      => trim($row['currency'] ?? 'RON'),
            'category'      => trim($row['category'] ?? ''),
            'image_url'     => trim($row['image_url'] ?? ''),
            'product_url'   => trim($row['product_url'] ?? ''),
            'affiliate_url' => trim($row['affiliate_url'] ?? ''),
            'description'   => trim($row['description'] ?? ''),
            'is_active'     => true,
            'source'        => 'sheet',
            'last_synced_at'=> now(),
        ];

        if ($externalId) {
            $existing = Product::where('external_id', $externalId)->first();

            if ($existing) {
                $existing->update($data);
                return 'updated';
            }

            $data['external_id'] = $externalId;
            $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
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
        if (empty($value)) return null;
        // Handle both 1.299,99 and 1299.99 formats
        $cleaned = str_replace(['.', ','], ['', '.'], trim($value));
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}
