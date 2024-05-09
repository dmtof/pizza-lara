@extends('templates.main')

@section('content')
    <div class="container">
        <div class="row">
            @foreach ($productItems as $productItem)
                <div class="col-lg-3 p-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $productItem->image) }}" class="card-img-top object-fit-cover"
                            alt="..." style="height: 200px;">
                        <div class="card-body">
                            <h4 class="card-title">{{ $productItem->name }}</h4>
                            <p class="card-text">Description: {{ $productItem->description }}</p>
                            <p class="card-text">Price: {{ $productItem->price }}</p>
                        </div>
                        <div class="card-footer p-0">
                            <a href="/cart/add/{{ $productItem->id }}" class="btn btn-primary w-100 btn-footer-form">Add to cart</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
