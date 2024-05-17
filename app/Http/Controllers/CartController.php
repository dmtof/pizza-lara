<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();
        if ($user) {
            return Cart::where('cart_id', $user->id)->firstOrFail();
        }

        return [
            'cart' => Cart::where('cart_id', session()->getId())->firstOrCreate(
                ['cart_id' => session()->getId()]
            )
        ];
    }

    private function addToCartLoop(int $id, int $quantity, $cart)
    {
        $productsArray = $cart->products;

        $totalPizza = 0;
        $totalDrink = 0;
        foreach ($productsArray as $product) {
            if ($product->category_id === 1) {
                $totalPizza += $product->pivot->quantity;
            } elseif ($product->category_id === 2) {
                $totalDrink += $product->pivot->quantity;
            }
        }

        $productItem = ProductItem::findOrFail($id);
        if ($productItem->category_id === 1 && $totalPizza + $quantity > 10) {
            return response()->json('Quantity must be less than or equal to 10', 422);
        }

        if ($productItem->category_id === 2 && $totalDrink + $quantity > 20) {
            return response()->json('Quantity must be less than or equal to 20', 422);
        }

        $existingProduct = $cart->products->find($id);
        if ($existingProduct) {
            $cart->products()->updateExistingPivot($id, [
                'quantity' => $existingProduct->pivot->quantity + $quantity
            ]);
        } else {
            $cart->products()->attach($id, ['quantity' => $quantity]);
        }

        return true;
    }

    public function addToCartProduct(Request $request, int $id)
    {
        if (!auth('sanctum')->check()) {
            $cart = Cart::where('cart_id', session()->getId())->firstOr(function () {
                return Cart::create([
                    'cart_id' => session()->getId(),
                ]);
            });
        }

        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $cart = Cart::where('cart_id', $user->id)->firstOrFail();
        }

        $quantity = intval($request->quantity ?? 1);

        $loop = $this->addToCartLoop($id, $quantity, $cart);
        if ($loop !== true) {
            return response()->json($loop->getData(), 422);
        }

        return [
            'cart' => $cart->load('products')
        ];
    }

    private function removeFromCartLoop(int $id, int $quantity, $cart)
    {
        $existingProduct = $cart->products->find($id);
        if ($existingProduct) {
            $newQuantity = $existingProduct->pivot->quantity - $quantity;
            if ($newQuantity <= 0) {
                $cart->products()->detach($id);
            } else {
                $cart->products()->updateExistingPivot($id, [
                    'quantity' => $newQuantity
                ]);
            }
        }

        return true;
    }

    public function removeFromCartProduct(Request $request, int $id)
    {
        if (!auth('sanctum')->check()) {
            $cart = Cart::where('cart_id', session()->getId())->firstOrFail();
        }

        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $cart = Cart::where('cart_id', $user->id)->firstOrFail();
        }

        $quantity = intval($request->quantity ?? 1);

        $this->removeFromCartLoop($id, $quantity, $cart);

        return [
            'cart' => $cart->load('products')
        ];
    }
}
