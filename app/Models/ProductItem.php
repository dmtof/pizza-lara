<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
