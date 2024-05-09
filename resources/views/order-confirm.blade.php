@extends('templates.main')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Order confirmed</h1>

                <p>Order id: <span class="">{{ $order->id }}</span></p>
            </div>
        </div>
    </div>

@endsection
