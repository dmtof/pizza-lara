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

        if (Auth::check()) {
            return response()->json('Error', 500);
        }

        return response()->json('User logged out successfully', 200);
    }

    public function login(LoginUserRequest $request)
    {
        $requestData = $request->validated();

        if (!Auth::attempt($requestData)) {
            return response()->json('Invalid credentials', 401);
        }

        return response()->json('User logged in successfully', 200);
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

        return response()->json([
            'user' => $user,
            'message' => 'User created successfully',
            'cart' => $cart,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }
}
