<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductItem extends Model
{
    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $table = 'product_items';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'image',
        'price',
        'created_at',
        'updated_at',
    ];

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
