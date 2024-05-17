<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return [
            'users' => User::all()
        ];
    }

    public function show(int $id)
    {
        $user = auth('sanctum')->user();

        if ($user->role === User::ROLE_ADMIN) {
            $userShow = User::findOrFail($id);
            return [
                'user' => $userShow
            ];
        }

        if ($user->id !== $id) {
            return response()->json('Page not found', 404);
        }

        return [
            'user' => User::findOrFail($id)
        ];
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        if (auth('sanctum')->user()->role !== User::ROLE_ADMIN && $user->id !== auth('sanctum')->user()->id) {
            return response()->json('Error', 500);
        }

        foreach ($request->all() as $key => $value) {
            if ($key === 'password') {
                $value = Hash::make($value);
            }
            $user->$key = $value;
        }

        $user->save();

        return [
            'user' => $user
        ];
    }

    public function destroy(int $id)
    {
        $orders = Order::where('user_id', $id)->get();
        foreach ($orders as $order) {
            $order->delete();
        }

        $user = User::findOrFail($id);
        $user->delete();

        return [
            'users' => User::all()
        ];
    }
}
