<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Statamic\Eloquent\Entries\UuidEntryModel;

class Product extends UuidEntryModel
{
    protected $attributes = [
        'site' => 'default',
        'collection' => 'products',
        'blueprint' => 'product',
        'published' => true,
        'data' => '[]',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('products', function (Builder $query): void {
            $query->where('collection', 'products');
        });

        static::creating(function (self $product): void {
            $product->collection = 'products';
            $product->blueprint = 'product';
            $product->site = $product->site ?: 'default';

            $data = is_array($product->data) ? $product->data : [];
            $product->data = $data;

            if (! $product->slug) {
                $product->slug = Str::slug(
                    (string) (Arr::get($data, 'model_number') ?: Arr::get($data, 'title') ?: Str::uuid())
                );
            }
        });
    }
}
