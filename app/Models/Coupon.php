<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'type', 'value', 'min_order_amount', 'max_discount',
        'usage_limit', 'usage_limit_per_user', 'used_count',
        'is_active', 'starts_at', 'expires_at',
        'applies_to', 'product_ids', 'category_ids', 'exclude_sale_items',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'exclude_sale_items' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'product_ids' => 'array',
            'category_ids' => 'array',
        ];
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    /**
     * Get orders that used this coupon.
     */
    public function orders()
    {
        return Order::where('coupon_code', $this->code);
    }

    /**
     * Calculate total revenue generated from orders using this coupon.
     */
    public function getRevenueAttribute(): float
    {
        return (float) Order::where('coupon_code', $this->code)->sum('total');
    }

    /**
     * Get display status.
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        if ($this->expires_at && $this->expires_at->isPast()) return 'expired';
        if ($this->starts_at && $this->starts_at->isFuture()) return 'scheduled';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'exhausted';
        return 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }
}
