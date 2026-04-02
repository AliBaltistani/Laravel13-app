<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('type')->default('simple'); // simple, variable
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('manage_stock')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('allow_backorder')->default(false);
            $table->unsignedInteger('sold_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('is_active');
            $table->index('category_id');
            $table->index('price');
            $table->index('slug');
            $table->index('is_featured');
            $table->index('is_new');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
