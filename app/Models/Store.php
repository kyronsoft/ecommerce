<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_name',
        'email',
        'phone',
        'location',
        'short_description',
        'description',
        'logo',
        'banner',
        'website',
        'instagram_url',
        'facebook_url',
        'whatsapp',
        'business_hours',
        'highlights',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'highlights' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $store): void {
            if (blank($store->slug)) {
                $store->slug = Str::slug($store->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function saleCommissions(): HasMany
    {
        return $this->hasMany(SaleCommission::class);
    }
}
