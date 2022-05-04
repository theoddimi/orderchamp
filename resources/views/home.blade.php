@extends('layouts.app')

@auth
 @if(auth()->user()->activeDiscounts()->count() > 0)
  @section("discount")
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <div class="alert alert-warning" style="width: 100%;" role="alert">
              <strong>Discount codes available in cart!</strong>
            </div>
       </div>
    </nav>
  @endsection
 @endif
@endauth

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Our Products') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                    </div>
                    @endif
                    
                     @if (session('error'))
                    <div class="alert alert-warning" role="alert">
                            {{ session('error') }}
                    </div>
                    @endif

                    <div class="d-flex flex-wrap justify-content-center">
                   @foreach($products as $product)
                        <form action="{{ route('cart.product.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="{{ asset('storage/'.$product->image)}}" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->short_description }}</p>
                                    <input type="hidden" name="product_id" value="{{ $product->id }}"/>
                                    <button class="btn btn-primary" {{ $product->quantity < 1 ? 'disabled' : ''}} >Add to cart</button>
                                </div>
                            </div>
                        </form>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
