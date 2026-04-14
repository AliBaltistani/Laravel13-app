<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // Stripe
            ['key' => 'payment.stripe_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Stripe', 'description' => 'Enable Stripe credit/debit card payments'],
            ['key' => 'payment.stripe_mode', 'value' => 'test', 'group' => 'payment', 'type' => 'select', 'label' => 'Stripe Mode', 'description' => 'Use "test" for development or "live" for production'],
            ['key' => 'payment.stripe_publishable_key', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'Stripe Publishable Key', 'description' => 'Your Stripe publishable/public key (pk_test_... or pk_live_...)'],
            ['key' => 'payment.stripe_secret_key', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'Stripe Secret Key', 'description' => 'Your Stripe secret key (sk_test_... or sk_live_...)'],
            ['key' => 'payment.stripe_webhook_secret', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'Stripe Webhook Secret', 'description' => 'Webhook signing secret for verifying Stripe events (whsec_...)'],

            // PayPal
            ['key' => 'payment.paypal_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable PayPal', 'description' => 'Enable PayPal payment gateway'],
            ['key' => 'payment.paypal_mode', 'value' => 'sandbox', 'group' => 'payment', 'type' => 'select', 'label' => 'PayPal Mode', 'description' => 'Use "sandbox" for testing or "live" for production'],
            ['key' => 'payment.paypal_client_id', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'PayPal Client ID', 'description' => 'Your PayPal REST API Client ID'],
            ['key' => 'payment.paypal_secret', 'value' => '', 'group' => 'payment', 'type' => 'password', 'label' => 'PayPal Secret', 'description' => 'Your PayPal REST API Secret'],

            // COD
            ['key' => 'payment.cod_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Cash on Delivery', 'description' => 'Allow customers to pay on delivery'],
            ['key' => 'payment.cod_instructions', 'value' => 'Pay with cash upon delivery.', 'group' => 'payment', 'type' => 'textarea', 'label' => 'COD Instructions', 'description' => 'Instructions shown to customers selecting COD'],

            // Bank Transfer
            ['key' => 'payment.bank_transfer_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean', 'label' => 'Enable Bank Transfer', 'description' => 'Allow customers to pay via bank transfer'],
            ['key' => 'payment.bank_transfer_details', 'value' => '', 'group' => 'payment', 'type' => 'textarea', 'label' => 'Bank Transfer Details', 'description' => 'Bank account details shown to customers (account name, number, bank, etc.)'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        $keys = [
            'payment.stripe_enabled', 'payment.stripe_mode',
            'payment.stripe_publishable_key', 'payment.stripe_secret_key',
            'payment.stripe_webhook_secret',
            'payment.paypal_enabled', 'payment.paypal_mode',
            'payment.paypal_client_id', 'payment.paypal_secret',
            'payment.cod_enabled', 'payment.cod_instructions',
            'payment.bank_transfer_enabled', 'payment.bank_transfer_details',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
