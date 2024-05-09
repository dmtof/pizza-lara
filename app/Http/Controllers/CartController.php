<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductItem;
use App\Models\User;
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

            return view('cart', ['cart' => $cart, 'productsArray' => $productsArray]);
        } else {
            return view('cart', ['cart' => $cart, 'productsArray' => []]);
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
            $cart = new Cart();
            $cart->session_id = session()->getId();
            $product[] = ['id' => $id, 'quantity' => 1];
            $cart->products = json_encode($product);
            $cart->save();
            session()->put('cart', $cart);
            session()->save();
        }

        return redirect('/cart');
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
    }

    public function confirm(Request $request)
    {
        $data = $request->all();

        if (auth()->check()) {
            $user = User::findOrFail(auth()->user()->id);
        } else {
            $user = User::create([
                'name' => 'test name',
                'email' => 'test email',
                'password' => 'test password',
            ]);
        }

        $order = Order::create([
            'products' => json_encode($data['products']),
            'total' => $data['total'],
            'address' => 'test address',
            'phone_number' => '1234567890',
            'name' => 'test name',
            'email' => 'test email',
            'note' => 'test note',
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json($order);
    }

    public function destroy(Request $request)
    {
        $id = intval($request->session()->get('cart')->id);
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return redirect('/cart');
    }
}
