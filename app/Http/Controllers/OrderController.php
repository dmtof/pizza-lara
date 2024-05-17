<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        if ($user->role === 1) {
            return [
                'orders' => Order::all()
            ];
        } else {
            return [
                'orders' => Order::where('user_id', $user->id)->get()
            ];
        }
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $requestData = $request->validated();

        $order = Order::findOrFail($id);
        foreach ($requestData as $key => $value) {
            $order->{$key} = $value;
        }
        $order->save();

        return [
            'order' => $order
        ];
    }

    public function confirm(ConfirmOrderRequest $request)
    {
        $requestData = $request->validated();

        $user = User::findOrFail(auth('sanctum')->user()->id);
        $cart = Cart::with('products')->where('cart_id', $user->id)->firstOrFail();

        $total = 0;
        foreach ($cart->products as $product) {
            $total += $product->price * $product->pivot->quantity;
        }

        $order = Order::create(
            array_merge($requestData, [
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'email' => $user->email
            ])
        );

        foreach ($cart->products as $product) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_item_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->price,
            ]);
        }

        $cart->products()->detach();

        return [
            'order' => $order->load('products')
        ];
    }
}
