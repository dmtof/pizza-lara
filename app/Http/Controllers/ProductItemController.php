<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductItemRequest;
use Illuminate\Http\Request;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Storage;

class ProductItemController extends Controller
{
    public function index()
    {
        return ProductItem::all();
    }

    public function store(StoreProductItemRequest $request)
    {
        $requestData = $request->validated();

        $productItem = ProductItem::create($requestData);

        if ($request->file('productImage')) {
            $productItem->image = Storage::disk('public')->put('images', $request->image);
            $productItem->save();
        }

        return $productItem;
    }

    public function show($id)
    {
        return ProductItem::findOrFail($id);
    }

    public function destroy($id)
    {
        $productItem = ProductItem::findOrFail($id);

        if ($productItem->image !== 'images/default-product.jpg') {
            Storage::disk('public')->delete($productItem->image);
        }

        $productItem->delete();

        return ProductItem::all();
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

        return $productItem;
    }
}
