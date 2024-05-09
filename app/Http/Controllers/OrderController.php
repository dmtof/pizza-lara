<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        return view('order-confirm', ['order' => $order]);
    }
}
