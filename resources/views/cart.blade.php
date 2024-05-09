@extends('templates.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="m-0">Cart</h1>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    @foreach($productsArray as $product)
                                        <div class="col-md-6">
                                            <p class="m-0">
                                                {{ $product->name }}
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="product-quantity form-control"
                                                   value="{{ $product->quantity }}">
                                        </div>
                                        <div class="col-md-2">
                                            <p class="m-0 text-end product-total"></p>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer">

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <p class="h1 text-end m-0">Total</p>
                    </div>

                    <div class="card-body">
                        <p class="m-0 text-end" id="orderTotal"></p>
                    </div>

                    <div class="card-footer">
                        <button class="order-confirm btn btn-primary w-100">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmed!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="order-confirmed-text">Your order has been confirmed. Order number is: <span
                                id="orderId"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const productsArray = @json($productsArray);
            const orderTotalElement = document.querySelector('#orderTotal');
            const productTotalElements = document.querySelectorAll('.product-total');
            const productQuantityElements = document.querySelectorAll('.product-quantity');

            function updateOrderTotal() {
                let total = 0;
                productsArray.forEach(product => {
                    total += product.price * product.quantity;
                });
                document.querySelector('#orderTotal').innerText = total;
            }

            updateOrderTotal();

            productTotalElements.forEach((productTotalElement, index) => {
                productTotalElement.innerText = productsArray[index].price * productsArray[index].quantity;
            });

            function updateProductTotal(index) {
                productTotalElements[index].innerText = productsArray[index].price * productsArray[index].quantity;
            }

            function saveChangesInCart() {
                fetch('/cart', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'products': productsArray,
                    })
                })
                    .catch(error => console.error(error));
            }

            // Изменения кол-ва у товаров
            productQuantityElements.forEach((productQuantityElement, index) => {
                productQuantityElement.addEventListener('change', () => {
                    let newQuantity = productQuantityElement.value;
                    productsArray[index].quantity = newQuantity;
                    updateOrderTotal();
                    updateProductTotal(index);
                    saveChangesInCart();
                });
            });


            const orderConfirmButton = document.querySelector('.order-confirm');

            function confirmOrder(data) {
                let orderNumber = data.id;
                let modal = new bootstrap.Modal('.modal');
                document.getElementById('orderId').innerText = orderNumber;
                modal.show();
                setTimeout(() => {
                    window.location.href = '/orders';
                }, 3000);
            }

            orderConfirmButton.addEventListener('click', () => {
                fetch('/cart/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'products': productsArray,
                        'total': orderTotalElement.innerText
                    })
                })
                    .then(response => response.json())
                    .then((data) => confirmOrder(data))
                    .catch(error => console.error(error));
            });

        </script>
@endsection
