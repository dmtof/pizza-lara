<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid login details',
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $cart = Cart::where('cart_id', $user->id)->firstOrFail();

            return response()->json([
                'status' => true,
                'cart' => $cart,
                'message' => 'User logged in successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 0
            ]);

            $cart = Cart::where('cart_id', session()->getId())->firstOr(function ($user) {
                Cart::create([
                    'cart_id' => $user->id,
                    'products' => json_encode([]),
                ]);
            });

            $cart->cart_id = $user->id;

            $cart->save();

            return response()->json([
                'status' => true,
                'cart' => $cart,
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken
            ]);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
