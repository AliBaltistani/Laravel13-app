<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'countries'];

    protected function casts(): array
    {
        return ['countries' => 'json'];
    }

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class)->orderBy('sort_order');
    }

    public function activeMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class)->where('is_active', true)->orderBy('sort_order');
    }
}
