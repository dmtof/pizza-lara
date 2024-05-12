<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');

        if (!empty($cart->products)) {
            $products = json_decode($cart->products, true);
            $productsArray = [];
            foreach ($products as $key => $product) {
                if (ProductItem::find($product['id'])) {
                    array_push($productsArray, ProductItem::find($product['id']));
                    $productsArray[$key]->quantity = $product['quantity'];
                } else {
                    echo 'error'; // TODO: throw error
                }
            }

            return response()->json(['cart' => $cart, 'productsArray' => $productsArray]);
        } else {
            return response()->json(['cart' => $cart, 'productsArray' => []]);
        }
    }

    public function add($id)
    {
//        session()->getHandler()->destroy(session()->getId());
//        dd(session()->get('cart'));

        $productItem = ProductItem::findOrFail($id);
        if (session()->has('cart')) {
            $sessionCart = session()->get('cart');
            $sessionProducts = json_decode($sessionCart->products, true);
            $collectSessionProducts = collect($sessionProducts);

            $index = $collectSessionProducts->search(function ($item) use ($id) {
                return $item['id'] === $id;
            });

            if ($index !== false) {
                $sessionProducts[$index]['quantity'] += 1;
            } else {
                $sessionProducts[] = ['id' => $id, 'quantity' => 1];
            }

            $sessionCart->products = json_encode($sessionProducts);

            $oldCart = Cart::findOrFail($sessionCart->id);
            $oldCart->update($sessionCart->toArray());
            $oldCart->products = json_encode($sessionProducts);
            $oldCart->save();
            session()->put('cart', $sessionCart);
            session()->save();
        } else {
            $cart = Cart::create([
                'session_id' => session()->getId(),
                'products' => json_encode([['id' => $id, 'quantity' => 1]]),
            ]);
            session()->put('cart', $cart);
            session()->save();
        }

        return response()->json(session()->get('cart'));
    }

    public function update(Request $request)
    {
        $sessionCart = session()->get('cart');
        $data = $request->all();

        $products = array();

        foreach ($data['products'] as $product) {
            $products[] = ['id' => str($product['id']), 'quantity' => intval($product['quantity'])];
        }

        $cart = Cart::findOrFail($sessionCart->id);
        $cart->products = json_encode($products);
        $cart->update();
        session()->put('cart', $cart);
        session()->save();

        return response()->json(session()->get('cart'));
    }

    public function destroy(Request $request)
    {
        $id = intval($request->session()->get('cart')->id);
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json('Your cart has been deleted!');
    }
}
