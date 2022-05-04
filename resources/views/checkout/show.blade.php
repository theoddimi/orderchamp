@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Checkout') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                    </div>
                    @endif
                    
                     @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    @auth
                        @if(auth()->user()->activeDiscounts()->count() > 0)
                    <div class="alert alert-warning" role="alert">
                            <h5>Discount codes:</h5>
                            @foreach(auth()->user()->activeDiscounts as $discount)
                                 <div  role="alert">
                                 {{ $discount->pivot->token }} <strong>({{  $discount->amount }}&euro; discount)</strong>
                                 </div>
                            @endforeach
                    </div>
                        @endif
                    @endauth

                    @if(count($cart->activeWithProducts) > 0)
                    <div class="">
                   @foreach($cart->activeWithProducts as $product)
                        <form action="{{ route('cart.product.delete') }}" method="POST" enctype="multipart/form-data">
                       {{ csrf_field() }}
                            <ul class="list-group mb-5">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <img width="150" class="img-thumbnail rounded float-left" src="{{ asset('storage/'.$product->image)}}" alt="Card image cap">
                                    <h5 class="">{{ $product->name }}</h5>
                                    <h5 class="">Price: {{ $product->price }} &euro;</h5>
                                    <p>{{ $product->pivot->quantity }}</p>
                                    <input type="hidden" name="product_id" value="{{ $product->id }}"/>
                                    <button class="btn btn-primary btn-sm">Remove from cart</button>
                                </li>
                            </ul>
                        </form>
                    @endforeach
                    </div>
                    <form method="POST" action="{{ route('checkout.complete') }}" >
                    @include('includes.checkout.contact-details') 
                        <button class="btn btn-primary" type="submit">Proceed checkout</button>
                    </form>
                    <div><p>Total checkout:</p><h3>{{ $cart->getTotalAmount() }} &euro;</h3></div>
                    @else
                    <h1>Cart is empty</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
