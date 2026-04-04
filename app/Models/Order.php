<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status', 'payment_status', 'payment_method',
        'payment_transaction_id', 'payment_details', 'currency_id', 'exchange_rate',
        'subtotal', 'discount_amount', 'coupon_code', 'shipping_amount',
        'shipping_method_name', 'tax_amount', 'total', 'customer_notes', 'admin_notes',
        'ip_address',
        'billing_first_name', 'billing_last_name', 'billing_company',
        'billing_address_line1', 'billing_address_line2', 'billing_city',
        'billing_state', 'billing_postal_code', 'billing_country',
        'billing_phone', 'billing_email',
        'shipping_first_name', 'shipping_last_name', 'shipping_company',
        'shipping_address_line1', 'shipping_address_line2', 'shipping_city',
        'shipping_state', 'shipping_postal_code', 'shipping_country', 'shipping_phone',
        'shipped_at', 'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_details' => 'json',
            'exchange_rate' => 'decimal:6',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Generate order number: ORD-YYYYMMDD-XXXXX
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "ORD-{$date}-{$random}";
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderByDesc('created_at');
    }

    public function statusHistories(): HasMany
    {
        return $this->statusHistory();
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Status helpers
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'secondary',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'warning',
            default => 'secondary',
        };
    }
}
