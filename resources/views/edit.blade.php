@extends('templates.main')

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Edit</h1>
                <form action="{{ route('updateProduct', $productItem->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="productName">Product name</label>
                        <input type="text" class="form-control" id="productName" name="productName"
                            value="{{ $productItem->name }}">

                        <label for="productDescription">Product description</label>
                        <input type="text" class="form-control" id="productDescription" name="productDescription"
                            value="{{ $productItem->description }}">

                        <label for="productPrice">Product price</label>
                        <input type="text" class="form-control" id="productPrice" name="productPrice"
                            value="{{ $productItem->price }}">

                        <label for="productImage">Product image</label>
                        <input type="file" class="form-control" id="productImage" name="productImage">
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
    </main>
@endsection
