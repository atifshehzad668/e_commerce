@extends('front.layouts.app')
@php
    use Gloudemans\Shoppingcart\Facades\Cart;
@endphp
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">

            <div class="row">
                <div class="col-md-2 mb-5">
                    <button id="clear-cart" class="btn btn-danger">Clear Cart</button>
                </div>
                <div class="col-md-10">
                    @if (Session::has('success'))
                        <div class="col-md-12 mb-5">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">

                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
                @if (Cart::count() > 0)
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table" id="cart">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($cartContents as $cartContent)
                                        <tr>
                                            <td>
                                                @if ($cartContent->options->productImage)
                                                    <img style="height: 50px; width: 20%"
                                                        src="{{ $cartContent->options->productImage }}"
                                                        alt="{{ $cartContent->name }}">
                                                @else
                                                    <img src="{{ asset('images/default-product-image.jpg') }}"
                                                        alt="Default Image">
                                                @endif
                                                <h2>{{ $cartContent->name }}</h2>
                                            </td>
                                            <td>{{ $cartContent->price }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub"
                                                            data-id="{{ $cartContent->rowId }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control form-control-sm border-0 text-center"
                                                        value="{{ $cartContent->qty }}">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add"
                                                            data-id="{{ $cartContent->rowId }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $cartContent->price * $cartContent->qty }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem('{{ $cartContent->rowId }}')"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card cart-summery">
                            <div class="sub-title">
                                <h2 class="bg-white">Cart Summary</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Subtotal</div>
                                    <div>${{ Cart::subtotal() }}</div>
                                </div>
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Shipping</div>
                                    <div>$</div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div>Total</div>
                                    <div>${{ Cart::subtotal() }}</div>
                                </div>
                                <div class="pt-5">
                                    <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to
                                        Checkout</a>
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupon mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control">
                            <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <h4>Your Cart Is Empty!</h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    {{-- <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('front.updateCart') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty // Use qty variable here
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                }
            });
        }

        $('#clear-cart').click(function() {
            $.ajax({
                url: '{{ route('cart.clear') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                }
            });
        });
    </script> --}}
    <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });

        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('front.updateCart') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty,

                },
                dataType: 'json',
                success: function(response) {

                    window.location.href = '{{ route('front.cart') }}';

                }
            });
        }

        function deleteItem(rowId) {
            if (confirm("Are You Sure You Want To Delete?")) {
                $.ajax({
                    url: '{{ route('front.deleteItem.cart') }}',
                    type: 'post',
                    data: {
                        rowId: rowId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                });
            }
        }


        $('#clear-cart').click(function() {
            $.ajax({
                url: '{{ route('cart.clear') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                }
            });
        });
    </script>
@endsection
