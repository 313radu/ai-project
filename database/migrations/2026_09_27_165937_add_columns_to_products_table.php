<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('slug')->unique()->after('name');
            $table->string('merchant')->nullable()->after('slug');
            $table->string('category')->nullable()->after('merchant');
            $table->text('description')->nullable()->after('category');
            $table->decimal('price', 10, 2)->default(0)->after('description');
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->string('image_url')->nullable()->after('original_price');
            $table->text('affiliate_url')->nullable()->after('image_url');
            $table->boolean('is_active')->default(true)->after('affiliate_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'slug', 'merchant', 'category', 'description',
                'price', 'original_price', 'image_url', 'affiliate_url', 'is_active',
            ]);
        });
    }
};
