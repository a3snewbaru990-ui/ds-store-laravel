<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'kicker',
        'title',
        'description',
        'image',
        'price_note',
        'primary_label',
        'primary_url',
        'secondary_label',
        'secondary_url',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image);
    }
}
