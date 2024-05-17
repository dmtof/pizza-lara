<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    /**
     * @var false|\Illuminate\Support\HigherOrderCollectionProxy|mixed|string
     */
    protected $table = 'carts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'cart_id',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductItem::class, 'cart_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
