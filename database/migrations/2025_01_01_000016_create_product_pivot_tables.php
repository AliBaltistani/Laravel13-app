<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'product_id']);
        });

        Schema::create('product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'tag_id']);
        });

        Schema::create('product_attribute', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'attribute_id']);
        });

        Schema::create('product_variant_attribute', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_variant_id', 'attribute_id']);
        });

        Schema::create('related_products', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('related_product_id');
            $table->foreign('related_product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->primary(['product_id', 'related_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_products');
        Schema::dropIfExists('product_variant_attribute');
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('category_product');
    }
};
