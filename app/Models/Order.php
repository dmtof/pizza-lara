<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'address',
        'phone_number',
        'name',
        'email',
        'note',
        'products',
        'total',
        'user_id',
        'status'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductItem::class, 'order_products')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
