<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columns = [
            'external_id' => static function (Blueprint $table): void {
                $table->string('external_id')->nullable()->index();
            },
            'network' => static function (Blueprint $table): void {
                $table->string('network')->nullable();
            },
            'brand' => static function (Blueprint $table): void {
                $table->string('brand')->nullable();
            },
            'old_price' => static function (Blueprint $table): void {
                $table->decimal('old_price', 10, 2)->nullable();
            },
            'currency' => static function (Blueprint $table): void {
                $table->string('currency', 3)->nullable();
            },
            'subcategory' => static function (Blueprint $table): void {
                $table->string('subcategory')->nullable();
            },
            'product_url' => static function (Blueprint $table): void {
                $table->text('product_url')->nullable();
            },
            'source' => static function (Blueprint $table): void {
                $table->string('source')->nullable();
            },
            'last_synced_at' => static function (Blueprint $table): void {
                $table->timestamp('last_synced_at')->nullable();
            },
        ];

        foreach ($columns as $column => $definition) {
            if (! Schema::hasColumn('products', $column)) {
                Schema::table('products', $definition);
            }
        }

        if (Schema::hasColumn('products', 'original_price')) {
            DB::table('products')
                ->whereNull('old_price')
                ->whereNotNull('original_price')
                ->update(['old_price' => DB::raw('original_price')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'external_id',
            'network',
            'brand',
            'old_price',
            'currency',
            'subcategory',
            'product_url',
            'source',
            'last_synced_at',
        ];

        $existingColumns = array_values(array_filter(
            $columns,
            static fn (string $column): bool => Schema::hasColumn('products', $column),
        ));

        if ($existingColumns !== []) {
            Schema::table('products', function (Blueprint $table) use ($existingColumns): void {
                $table->dropColumn($existingColumns);
            });
        }
    }
};
