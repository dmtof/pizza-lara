<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'address',
        'phone_number',
        'name',
        'email',
        'note',
        'products',
        'total'
    ];
}
