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
        return response()->json($productItem);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $productItem = ProductItem::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->file('productImage')) {
            $productItem->image = Storage::disk('public')->put('images', $request->image);
        }

        $productItem->save();

        return response()->json($productItem);
    }

    public function show($id)
    {
        $productItem = ProductItem::findOrFail($id);

        return response()->json($productItem);
    }

    public function destroy($id)
    {
        $productItem = ProductItem::findOrFail($id);

        if ($productItem->image !== 'images/default-product.jpg') {
            Storage::disk('public')->delete($productItem->image);
        }

        $productItem->delete();

        $productItems = ProductItem::all();

        return response()->json($productItems);
    }

    public function update(Request $request, $id)
    {
        $productItem = ProductItem::findOrFail($id);

        foreach ($request->all() as $key => $value) {
            if ($value) {
                $productItem->$key = $value;
            }
        }

        $productItem->update();

        return response()->json($productItem);
    }
}
