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
            'cart' => Cart::firstOrCreate([
                'cart_id' => session()->getId(),
                'products' => json_encode([])
            ])
        ];
    }

    private function addToCartLoop(int $id, int $quantity, $cart)
    {
        $productsArray = json_decode($cart->products, true);

        $totalPizza = 0;
        $totalDrink = 0;
        foreach ($productsArray as $product) {
            $productItem = ProductItem::findOrFail($product['id']);
            if ($productItem->category_id === 1) {
                $totalPizza += $product['quantity'];
            } elseif ($productItem->category_id === 2) {
                $totalDrink += $product['quantity'];
            }
        }

        $productItem = ProductItem::findOrFail($id);
        if ($productItem->category_id === 1 && $totalPizza + $quantity > 10) {
            return response()->json('Quantity must be less than or equal to 10', 422);
        }

        if ($productItem->category_id === 2 && $totalDrink + $quantity > 20) {
            return response()->json('Quantity must be less than or equal to 20', 422);
        }

        if (!empty($productsArray)) {
            foreach ($productsArray as $key => $product) {
                if ($product['id'] === $id) {
                    $productsArray[$key]['quantity'] += $quantity;
                    break;
                } else {
                    if ($key === count($productsArray) - 1) {
                        $productsArray[] = ['id' => $id, 'quantity' => $quantity];
                    }
                }
            }
        } else {
            $productsArray[] = ['id' => $id, 'quantity' => $quantity];
        }

        $cart->products = json_encode($productsArray);

        $cart->save();

        return true;
    }

    public function addToCartProduct(Request $request, int $id)
    {
        if (!auth('sanctum')->check()) {
            $cart = Cart::firstOrNew(
                ['cart_id' => session()->getId()],
                ['products' => json_encode([])]
            );
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
            'cart' => $cart
        ];
    }

    private function removeFromCartLoop(int $id, int $quantity, $cart)
    {
        $productsArray = json_decode($cart->products, true);

        if (!empty($productsArray)) {
            foreach ($productsArray as $key => $product) {
                if ($product['id'] === $id) {
                    $productsArray[$key]['quantity'] -= $quantity;
                    if ($productsArray[$key]['quantity'] <= 0) {
                        unset($productsArray[$key]);
                    }
                }
            }
        }

        $cart->products = json_encode($productsArray);

        $cart->save();

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
            'cart' => $cart
        ];
    }
}
