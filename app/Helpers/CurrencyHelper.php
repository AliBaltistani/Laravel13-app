<?php

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Format a price with the configured currency symbol.
     */
    public static function format(float $amount): string
    {
        $symbol = Setting::get('general.currency_symbol', '$');
        $position = Setting::get('general.currency_position', 'before');
        $formatted = number_format($amount, 2);

        return $position === 'after'
            ? $formatted . $symbol
            : $symbol . $formatted;
    }
}
