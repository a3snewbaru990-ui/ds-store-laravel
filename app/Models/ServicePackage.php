<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePackage extends Model
{
    /**
     * @var array<string, string>
     */
    public const RANK_OPTIONS = [
        'grandmaster' => 'Grandmaster',
        'epic' => 'Epic',
        'legend' => 'Legend',
        'mythic' => 'Mythic',
        'mythic-honor' => 'Mythic Honor',
        'mythic-glory' => 'Mythic Glory',
        'mythic-immortal' => 'Mythic Immortal',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'slug',
        'rank_key',
        'price',
        'unit',
        'badge',
        'tagline',
        'description',
        'emblem',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getPriceLabelAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getUnitLabelAttribute(): string
    {
        return $this->unit === 'star' ? 'bintang' : 'paket';
    }

    public function getEmblemUrlAttribute(): string
    {
        return asset($this->emblem ?: 'images/rank-' . $this->rank_key . '.webp');
    }

    public function isStarPackage(): bool
    {
        return $this->unit === 'star';
    }

    public function starsIncluded(): int
    {
        return $this->isStarPackage() ? 1 : 10;
    }
}
