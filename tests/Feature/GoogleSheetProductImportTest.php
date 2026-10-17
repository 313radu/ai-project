<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\GoogleSheetProductImporter;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleSheetProductImportTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_active_sheet_rows_are_imported_and_rendered(): void
    {
        $sheetUrl = 'https://sheet.test/products.csv';

        config()->set('services.google_sheet_products_url', $sheetUrl);

        Http::preventStrayRequests();
        Http::fake([
            $sheetUrl => Http::response(implode("\n", [
                'external_id,name,merchant,network ,brand ,price ,old_price,currency ,category,iimage_url,product_url,affiliate_url,description,is_active',
                '111,iPhone 17,altex,2performant,Apple,"1.299,99","1.599,99",rion,smartphones,https://images.test/iphone.jpg,https://altex.test/iphone,https://affiliate.test/iphone,"Iphone 17 128 gb",active',
            ])),
        ]);

        $stats = app(GoogleSheetProductImporter::class)->import();

        $this->assertSame([
            'created' => 1,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
        ], $stats);

        $this->assertDatabaseHas('products', [
            'external_id' => '111',
            'name' => 'iPhone 17',
            'image_url' => 'https://images.test/iphone.jpg',
            'price' => '1299.99',
            'old_price' => '1599.99',
            'currency' => 'RON',
            'is_active' => true,
        ]);

        $product = Product::where('external_id', '111')->firstOrFail();

        $this->get('/')->assertOk()->assertSee('iPhone 17');
        $this->get('/products')->assertOk()->assertSee('iPhone 17');
        $this->get(route('products.show', $product))->assertOk()->assertSee('iPhone 17');
    }
}
