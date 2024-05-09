<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id',
        'product_items_id',
        'quantity',
        'created_at',
        'updated_at',
    ];
}
