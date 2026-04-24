<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    private const IMAGE_PLACEHOLDER = 'wolmart/assets/images/placeholders/product-placeholder.svg';

    protected $fillable = [
        'category_id', 'store_id', 'name', 'slug', 'sku', 'short_description', 'description', 'price',
        'compare_price', 'stock', 'is_active', 'is_featured', 'image', 'gallery'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'gallery' => 'array',
    ];

    protected $appends = [
        'image_url',
        'gallery_urls',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product): void {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->image, 'image');
    }

    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($path, $index) => $this->resolveMediaUrl($path, 'gallery', $index))
            ->filter()
            ->values()
            ->all();
    }

    public function resolveMediaUrl(?string $path, string $field = 'image', ?int $index = null): string
    {
        if (blank($path)) {
            return asset(self::IMAGE_PLACEHOLDER);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = static::normalizeMediaPath($path);

        if ($normalizedPath !== null && Storage::disk('public')->exists($normalizedPath)) {
            $routeParameters = ['product' => $this];

            if ($field === 'gallery') {
                $routeParameters['field'] = 'gallery';
                $routeParameters['index'] = $index;
            }

            return route('store.product.media', $routeParameters);
        }

        return asset(ltrim($path, '/'));
    }

    public static function normalizeMediaPath(?string $path): ?string
    {
        if (blank($path) || Str::startsWith((string) $path, ['http://', 'https://'])) {
            return null;
        }

        $normalizedPath = ltrim((string) $path, '/');

        if (Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/');
        }

        return $normalizedPath;
    }
}
