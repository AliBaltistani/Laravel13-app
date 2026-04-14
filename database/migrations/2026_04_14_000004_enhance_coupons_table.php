<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('applies_to')->default('all')->after('is_active'); // all, specific_products, specific_categories
            $table->json('product_ids')->nullable()->after('applies_to');
            $table->json('category_ids')->nullable()->after('product_ids');
            $table->boolean('exclude_sale_items')->default(false)->after('category_ids');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['description', 'applies_to', 'product_ids', 'category_ids', 'exclude_sale_items']);
        });
    }
};
