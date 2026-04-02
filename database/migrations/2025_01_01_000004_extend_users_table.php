<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone', 20)->nullable()->after('last_name');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->boolean('newsletter_subscribed')->default(false)->after('is_active');
            $table->foreignId('preferred_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->after('newsletter_subscribed');
            $table->string('google_id')->nullable()->after('preferred_currency_id');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('preferred_currency_id');
            $table->dropColumn(['first_name', 'last_name', 'phone', 'avatar', 'is_active', 'newsletter_subscribed', 'google_id']);
        });
    }
};
