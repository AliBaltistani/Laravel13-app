<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
        'newsletter_subscribed',
        'preferred_currency_id',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'newsletter_subscribed' => 'boolean',
        ];
    }

    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }
        return $this->name ?? '';
    }

    public function preferredCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'preferred_currency_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function defaultShippingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)->where('is_default_shipping', true);
    }

    public function defaultBillingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)->where('is_default_billing', true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withPivot('product_variant_id', 'added_at');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
