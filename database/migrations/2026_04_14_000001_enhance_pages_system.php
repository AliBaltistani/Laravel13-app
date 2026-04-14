<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance pages table with new columns
        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('image');
            $table->string('video_url')->nullable()->after('banner_image');
            $table->string('video_file')->nullable()->after('video_url');
            $table->longText('custom_css')->nullable()->after('template');
            $table->longText('custom_js')->nullable()->after('custom_css');
            $table->string('layout')->default('default')->after('custom_js'); // default, full-width, with-sidebar
            $table->boolean('show_sidebar')->default(false)->after('layout');
            $table->longText('sidebar_content')->nullable()->after('show_sidebar');
        });

        // Page gallery images
        Schema::create('page_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Page content sections (modular blocks)
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('text'); // text, image, video, html, banner, gallery
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('css_class')->nullable();
            $table->string('bg_color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_images');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'banner_image', 'video_url', 'video_file',
                'custom_css', 'custom_js', 'layout',
                'show_sidebar', 'sidebar_content',
            ]);
        });
    }
};
