<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('display_type')->default('select'); // select, color, button
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('value')->nullable(); // hex color value
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['attribute_group_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_groups');
    }
};
