@extends('templates.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="m-0">Add product</h3>
                    </div>
                    <form class="add-products-form" action="/admin" method="POST" enctype="multipart/form-data">
                        <div class="card-body p-4">
                            @csrf
                            <div class="row mb-3">
                                <input class="form-control" type="text" name="productName" placeholder="Name of product">
                            </div>
                            <div class="row mb-3">
                                <input class="form-control" type="text" name="productPrice" placeholder="Product Price">
                            </div>
                            <div class="row mb-3">
                                <input class="form-control" type="text" name="productDescription"
                                    placeholder="Product Description">
                            </div>
                            <div class="row">
                                <input class="form-control" type="file" name="productImage">
                            </div>
                        </div>
                        <div class="card-footer p-0">
                            <div class="row m-0">
                                <input class="btn btn-success btn-footer-form " type="submit" value="Add Pizza">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                @foreach ($productItems->reverse() as $productItem)
                    <div class="card mb-4">
                        <img class="card-img-top object-fit-cover" style="max-height: 200px;"
                            src="{{ asset('storage/' . $productItem->image) }}">
                        <div class="card-body">
                            <p class="display-5">{{ $productItem->name }}</p>
                            <p>Product id: <span class="">{{ $productItem->id }}</span></p>
                            <p>Product price: <span class="">{{ $productItem->price }}</span></p>
                            <p>Product description: <span class="">{{ $productItem->description }}</span></p>
                            <form action="{{ route('deleteProduct', $productItem->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input class="btn btn-danger mb-3" type="submit" value="Delete">
                            </form>
                            <a class="btn btn-info" href="{{ route('editProduct', $productItem->id) }}">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
