<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Image\Enums\Fit;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    /**
     * Get all of the product_images for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 100, 'h' => 100])
            ->performOnCollections('images')
            ->nonQueued();
    }
<<<<<<< HEAD
}
=======

    /**
     * Get the orderitem that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderitems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }
}
>>>>>>> origin/master
