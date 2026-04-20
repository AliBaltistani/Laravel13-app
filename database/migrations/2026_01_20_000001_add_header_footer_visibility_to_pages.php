<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('pages', 'show_in_header')) {
                $table->boolean('show_in_header')->default(false)->after('is_active')->comment('Display link in header navigation');
            }
            if (!Schema::hasColumn('pages', 'show_in_footer')) {
                $table->boolean('show_in_footer')->default(false)->after('show_in_header')->comment('Display link in footer navigation');
            }
            if (!Schema::hasColumn('pages', 'header_label')) {
                $table->string('header_label')->nullable()->after('show_in_footer')->comment('Custom label for header menu');
            }
            if (!Schema::hasColumn('pages', 'footer_label')) {
                $table->string('footer_label')->nullable()->after('header_label')->comment('Custom label for footer menu');
            }
            if (!Schema::hasColumn('pages', 'header_order')) {
                $table->integer('header_order')->default(0)->after('footer_label')->comment('Display order in header');
            }
            if (!Schema::hasColumn('pages', 'footer_order')) {
                $table->integer('footer_order')->default(0)->after('header_order')->comment('Display order in footer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['show_in_header', 'show_in_footer', 'header_label', 'footer_label', 'header_order', 'footer_order']);
        });
    }
};
