<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'iso_code' => 'USD', 'symbol' => '$', 'exchange_rate' => 1.000000, 'is_default' => true],
            ['name' => 'Euro', 'iso_code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 0.920000],
            ['name' => 'British Pound', 'iso_code' => 'GBP', 'symbol' => '£', 'exchange_rate' => 0.790000],
            ['name' => 'Japanese Yen', 'iso_code' => 'JPY', 'symbol' => '¥', 'exchange_rate' => 149.500000],
            ['name' => 'Canadian Dollar', 'iso_code' => 'CAD', 'symbol' => 'C$', 'exchange_rate' => 1.360000],
            ['name' => 'Australian Dollar', 'iso_code' => 'AUD', 'symbol' => 'A$', 'exchange_rate' => 1.530000],
            ['name' => 'Swiss Franc', 'iso_code' => 'CHF', 'symbol' => 'CHF', 'exchange_rate' => 0.880000],
            ['name' => 'Chinese Yuan', 'iso_code' => 'CNY', 'symbol' => '¥', 'exchange_rate' => 7.240000],
            ['name' => 'Indian Rupee', 'iso_code' => 'INR', 'symbol' => '₹', 'exchange_rate' => 83.100000],
            ['name' => 'Pakistani Rupee', 'iso_code' => 'PKR', 'symbol' => 'Rs', 'exchange_rate' => 278.500000],
            ['name' => 'Saudi Riyal', 'iso_code' => 'SAR', 'symbol' => 'SR', 'exchange_rate' => 3.750000],
            ['name' => 'UAE Dirham', 'iso_code' => 'AED', 'symbol' => 'AED', 'exchange_rate' => 3.670000],
            ['name' => 'Brazilian Real', 'iso_code' => 'BRL', 'symbol' => 'R$', 'exchange_rate' => 4.970000],
            ['name' => 'South African Rand', 'iso_code' => 'ZAR', 'symbol' => 'R', 'exchange_rate' => 18.600000],
            ['name' => 'Turkish Lira', 'iso_code' => 'TRY', 'symbol' => '₺', 'exchange_rate' => 30.200000],
            ['name' => 'Russian Ruble', 'iso_code' => 'RUB', 'symbol' => '₽', 'exchange_rate' => 91.500000],
            ['name' => 'Mexican Peso', 'iso_code' => 'MXN', 'symbol' => 'MX$', 'exchange_rate' => 17.100000],
            ['name' => 'South Korean Won', 'iso_code' => 'KRW', 'symbol' => '₩', 'exchange_rate' => 1320.000000],
            ['name' => 'Singapore Dollar', 'iso_code' => 'SGD', 'symbol' => 'S$', 'exchange_rate' => 1.340000],
            ['name' => 'Malaysian Ringgit', 'iso_code' => 'MYR', 'symbol' => 'RM', 'exchange_rate' => 4.700000],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
