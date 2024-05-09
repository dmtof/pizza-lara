<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Storage;

class ProductItemController extends Controller
{
    public function index()
    {
        $productItem = ProductItem::all();
        return view('catalog', ['productItem' => $productItem]);
    }

    public function create()
    {
        return view('create');
    }

    public function edit($id)
    {
        $productItem = ProductItem::find($id);
        return view('edit', ['productItem' => $productItem]);
    }

    public function store(Request $request)
    {
        $productItem = new ProductItem;
        $productItem->name = $request->input('productName');
        $productItem->description = $request->input('productDescription');
        $productItem->image = Storage::disk('public')->put('images', $request->file('productImage'));
        $productItem->price = $request->input('productPrice');
        $productItem->save();
        return redirect('/admin');
    }

    public function show($id)
    {
        $productItem = ProductItem::findOrFail($id);
        return view('show', ['productItem' => $productItem]);
    }

    public function destroy($id)
    {
        $productItem = ProductItem::findOrFail($id);
        Storage::disk('public')->delete($productItem->image);
        $productItem->delete();

        return redirect('/admin');
    }

    public function update(Request $request, $id)
    {
        $productItem = ProductItem::find($id);
        $productItem->name = $request->input('productName');
        $productItem->description = $request->input('productDescription');
        if ($request->file('productImage')) {
            Storage::disk('public')->delete($productItem->image);
            $productItem->image = Storage::disk('public')->put('images', $request->file('productImage'));
        }
        $productItem->price = $request->input('productPrice');
        $productItem->save();
        return redirect('/admin');
    }
}
