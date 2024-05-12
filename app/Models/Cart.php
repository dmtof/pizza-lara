<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * @var false|\Illuminate\Support\HigherOrderCollectionProxy|mixed|string
     */
    protected $table = 'carts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id',
        'products',
        'created_at',
        'updated_at',
    ];
}
