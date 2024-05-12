<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function confirm(Request $request)
    {
        if (!auth()->check()) {
            return response()->json('Please login first');
        }

        if (empty($request->products)) {
            return response()->json('Please add some products to cart first');
        }

        $user = User::findOrFail(auth()->user()->id);

        $order = Order::create([
            'products' => $request->products,
            'total' => $request->total,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'note' => $request->note,
            'status' => 'pending',
            'email' => $user->email,
            'user_id' => $user->id,
        ]);

        return response()->json($order);
    }
}
