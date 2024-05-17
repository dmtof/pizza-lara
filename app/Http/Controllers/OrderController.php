<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
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

        return [
            'order' => Order::create(
                array_merge($requestData, [
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total' => 0,
                    'email' => $user->email
                ])
            )
        ];

    }
}
