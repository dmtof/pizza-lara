<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return [
            'message' => 'User logged out successfully',
        ];
    }

    public function login(LoginUserRequest $request)
    {
        $requestData = $request->validated();

        if (!Auth::attempt($requestData)) {
            return [
                'message' => 'Invalid credentials',
            ];
        }

        return [
            'user' => User::where('email', $requestData['email'])->firstOrFail(),
            'message' => 'User logged in successfully',
        ];
    }

    public function register(RegisterUserRequest $request)
    {
        $requestData = $request->validated();

        $user = User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => Hash::make($requestData['password']),
            'role' => 0
        ]);

        $cart = Cart::where('cart_id', session()->getId())->firstOr(function () {
            return Cart::create([
                'cart_id' => session()->getId(),
                'products' => json_encode([]),
            ]);
        });

        $cart->cart_id = $user->id;

        $cart->save();

        return [
            'message' => 'User created successfully',
            'cart' => $cart,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];
    }
}
