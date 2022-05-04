@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Order Info') }}</div>

                <div class="card-body">


                    <div>
                  @if(null !== $checkout)
                        <div>Order number: {{ $checkout->id }}</div>
                        <div>Total amount: {{ $checkout->total_amount }}</div>
                        <div>Discount: {{ $checkout->discount ?? 0}}</div>
                        <div>Paid amount: {{ $checkout->paid_amount }}</div>
                   @else 
                        <h2>Order details not found</h2>
                   @endif 
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

        
    
