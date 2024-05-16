<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductItem;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json('Unauthorized', 401);
        }

        if ($user->role === 1) {
            $orders = Order::all();
            return response()->json($orders);
        } else {
            $orders = Order::where('user_id', $user->id)->get();
            return response()->json($orders);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $order = Order::findOrFail($id);
        foreach ($request->all() as $key => $value) {
            $order->{$key} = $value;
        }
        $order->save();

        return response()->json($order);
    }

    public function confirm(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json('Please login', 401);
        }

        $request->validate([
            'products' => 'required',
        ]);

        if (empty($request->products)) {
            return response()->json('Please add some products to cart first');
        }


//        $pizza_limit = 10;
//        $drink_limit = 20;
//
//        $products = json_decode($request->products, true);
//
//        foreach ($products as $product => $item) {
//            $id = $item['id'];
//            $productItem = ProductItem::findOrFail($id);
//
//            if ($productItem->category_id === 1 && $item['quantity'] > $pizza_limit) {
//                return response()->json('You can only add up to ' . $pizza_limit . ' pizza(s)');
//            }
//
//            if ($productItem->category_id === 2 && $item['quantity'] > $drink_limit) {
//                return response()->json('You can only add up to ' . $drink_limit . ' drink(s)');
//            }
//        }

        $user = User::findOrFail(auth('sanctum')->user()->id);

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
