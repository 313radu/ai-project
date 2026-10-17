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
            $response = Http::connectTimeout(5)
                ->timeout(30)
                ->get($this->sheetUrl);

            if (! $response->successful()) {
                Log::error('GoogleSheetImporter: Failed to fetch sheet', [
                    'status' => $response->status(),
                    'url' => $this->sheetUrl,
                ]);

                return $stats;
            }

            $body = $response->body();

            if (stripos(ltrim($body), '<!doctype html') === 0 || stripos(ltrim($body), '<html') === 0) {
                Log::error('GoogleSheetImporter: URL returned HTML, not CSV. Use File > Share > Publish to web > CSV.');

                return $stats;
            }

            $rows = $this->parseCsv($body);

            if (empty($rows)) {
                Log::warning('GoogleSheetImporter: Sheet returned 0 rows — check columns match expected headers.');

                return $stats;
            }

            foreach ($rows as $index => $row) {
                try {
                    $result = $this->upsertProduct($row);
                    $stats[$result]++;
                } catch (\Exception $e) {
                    Log::warning("GoogleSheetImporter: Row {$index} error: ".$e->getMessage(), $row);
                    $stats['errors']++;
                }
            }
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('GoogleSheetImporter: Unexpected error — '.$e->getMessage());
        }

        return $stats;
    }

    protected function parseCsv(string $csv): array
    {
        $csv = trim(preg_replace('/^\xEF\xBB\xBF/', '', $csv) ?? $csv);

        if ($csv === '') {
            return [];
        }

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $csv);
        rewind($stream);

        $headerRow = fgetcsv($stream);

        if (! $headerRow) {
            fclose($stream);

            return [];
        }

        $headers = array_map(
            fn ($header): string => $this->normalizeHeader((string) $header),
            $headerRow
        );
        $count = count($headers);
        $rows = [];

        while (($values = fgetcsv($stream)) !== false) {
            if ($values === [null] || trim(implode('', $values)) === '') {
                continue;
            }

            $values = array_slice(array_pad($values, $count, ''), 0, $count);
            $rows[] = array_combine($headers, $values);
        }

        fclose($stream);

        return $rows;
    }

    protected function upsertProduct(array $row): string
    {
        if (empty(trim($row['name'] ?? ''))) {
            return 'skipped';
        }

        $externalId = trim($row['external_id'] ?? '');

        $data = [
            'name' => trim($row['name']),
            'merchant' => trim($row['merchant'] ?? ''),
            'network' => trim($row['network'] ?? '') ?: 'manual',
            'brand' => trim($row['brand'] ?? ''),
            'price' => $this->parseDecimal($row['price'] ?? null) ?? 0,
            'old_price' => $this->parseDecimal($row['old_price'] ?? null),
            'currency' => $this->normalizeCurrency($row['currency'] ?? null),
            'category' => trim($row['category'] ?? ''),
            'subcategory' => trim($row['subcategory'] ?? ''),
            'image_url' => trim($row['image_url'] ?? ''),
            'product_url' => trim($row['product_url'] ?? ''),
            'affiliate_url' => trim($row['affiliate_url'] ?? ''),
            'description' => trim($row['description'] ?? ''),
            'is_active' => $this->parseBoolean($row['is_active'] ?? null),
            'source' => 'sheet',
            'last_synced_at' => now(),
        ];

        if ($externalId !== '') {
            $existing = Product::where('external_id', $externalId)->first();

            if ($existing) {
                $existing->update($data);

                return 'updated';
            }

            $data['external_id'] = $externalId;
            $data['slug'] = $this->makeSlug($data['name']);
            Product::create($data);

            return 'created';
        }

        $existing = Product::where('name', $data['name'])
            ->where('merchant', $data['merchant'])
            ->first();

        if ($existing) {
            $existing->update($data);

            return 'updated';
        }

        $data['slug'] = $this->makeSlug($data['name']);
        Product::create($data);

        return 'created';
    }

    protected function makeSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';

        return $base.'-'.Str::lower(Str::random(6));
    }

    protected function parseDecimal(?string $value): ?float
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d,\.\-]/', '', trim($value));

        if ($cleaned === null || $cleaned === '' || ! preg_match('/\d/', $cleaned)) {
            return null;
        }

        $commaPosition = strrpos($cleaned, ',');
        $dotPosition = strrpos($cleaned, '.');

        if ($commaPosition !== false && $dotPosition !== false) {
            if ($commaPosition > $dotPosition) {
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif ($commaPosition !== false) {
            $fractionLength = strlen($cleaned) - $commaPosition - 1;
            $cleaned = $fractionLength <= 2
                ? str_replace(',', '.', $cleaned)
                : str_replace(',', '', $cleaned);
        } elseif ($dotPosition !== false) {
            $fractionLength = strlen($cleaned) - $dotPosition - 1;
            $cleaned = $fractionLength > 2
                ? str_replace('.', '', $cleaned)
                : $cleaned;
        }

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    protected function normalizeHeader(string $header): string
    {
        $header = preg_replace('/^\xEF\xBB\xBF/', '', $header) ?? $header;
        $header = Str::lower(trim($header));

        return match ($header) {
            'iimage_url', 'image', 'image url' => 'image_url',
            'original_price', 'old price' => 'old_price',
            'affiliate url', 'link' => 'affiliate_url',
            'product url' => 'product_url',
            'active' => 'is_active',
            default => $header,
        };
    }

    protected function parseBoolean(mixed $value): bool
    {
        $normalized = Str::lower(trim((string) $value));

        return match ($normalized) {
            '', '1', 'true', 'yes', 'y', 'on', 'active', 'enabled' => true,
            '0', 'false', 'no', 'n', 'off', 'inactive', 'disabled' => false,
            default => filter_var($normalized, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true,
        };
    }

    protected function normalizeCurrency(?string $value): string
    {
        $currency = Str::upper(trim((string) $value));

        return match ($currency) {
            '', 'RION', 'LEI' => 'RON',
            default => substr($currency, 0, 3),
        };
    }
}
