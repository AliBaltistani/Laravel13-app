<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderSlide extends Model
{
    protected $fillable = [
        'slider_id', 'title', 'subtitle', 'description',
        'button_text', 'button_url', 'secondary_button_text', 'secondary_button_url',
        'image_desktop', 'image_mobile', 'text_color',
        'sort_order', 'is_active', 'starts_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
