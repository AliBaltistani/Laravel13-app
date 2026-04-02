<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    protected $fillable = [
        'shipping_zone_id', 'name', 'type', 'price',
        'min_order_amount', 'max_order_amount', 'min_weight', 'max_weight',
        'estimated_days', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_order_amount' => 'decimal:2',
            'min_weight' => 'decimal:2',
            'max_weight' => 'decimal:2',
            'estimated_days' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
