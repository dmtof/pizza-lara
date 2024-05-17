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
            return Order::all();
        } else {
            return Order::where('user_id', $user->id)->get();
        }
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $requestData = $request->validated();

        $order = Order::findOrFail($id);
        foreach ($requestData as $key => $value) {
            $order->{$key} = $value;
        }
        $order->save();

        return $order;
    }

    public function confirm(ConfirmOrderRequest $request)
    {
        $requestData = $request->validated();

        $user = User::findOrFail(auth('sanctum')->user()->id);

        return Order::create(
            array_merge(
                $requestData,
                [
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total' => 0,
                    'email' => $user->email
                ]
            )
        );
    }
}
