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
        return [
            'products' => ProductItem::all()
        ];
    }

    public function store(StoreProductItemRequest $request)
    {
        $requestData = $request->validated();

        $productItem = ProductItem::create($requestData);

        if ($request->file('productImage')) {
            $productItem->image = Storage::disk('public')->put('images', $request->image);
            $productItem->save();
        }

        return [
            'product' => $productItem
        ];
    }

    public function show(int $id)
    {
        return [
            'product' => ProductItem::findOrFail($id)
        ];
    }

    public function destroy(int $id)
    {
        $productItem = ProductItem::findOrFail($id);

        if ($productItem->image !== 'images/default-product.jpg') {
            Storage::disk('public')->delete($productItem->image);
        }

        $productItem->delete();

        return [
            'products' => ProductItem::all()
        ];
    }

    public function update(Request $request, int $id)
    {
        $productItem = ProductItem::findOrFail($id);
        $productItem->update($request->all());

        return [
            'product' => $productItem
        ];
    }
}
