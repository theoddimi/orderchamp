<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Contact Details') }}</div>
                <div class="card-body">
                        @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if(old('name')){{old('name')}}@else{{false === auth()->check() ? '' :auth()->user()->name}}@endif"  autocomplete="name" autofocus>

                                @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="@if(old('email')){{old('email')}}@else{{false === auth()->check() ? '' : auth()->user()->email}}@endif "  autocomplete="email">

                                @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Delivery Address') }}</label>

                        <div class="col-md-6">
                            <input id="delivery_address" type="text" class="form-control @error('delivery_address') is-invalid @enderror" name="delivery_address" value="@if(old('delivery_address')){{old('delivery_address')}}@else{{true === auth()->check() && auth()->user()->details()->exists()? auth()->user()->details()->first()->delivery_address : ''}}@endif"  autocomplete="name" autofocus>

                                @error('delivery_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                        <div class="col-md-6">
                            <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="@if(old('phone_number')) {{old('phone_number')}}@else{{true === auth()->check() && auth()->user()->details()->exists()? auth()->user()->details()->first()->phone_number : ''}}@endif"  autocomplete="name" autofocus>

                                @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discount_voucher" class="col-md-4 col-form-label text-md-right">{{ __('Discount Voucher') }}</label>

                        <div class="col-md-6">
                            <input id="discount_voucher" type="text" class="form-control @error('discount_voucher') is-invalid @enderror" name="discount_voucher" value="{{ old('discount_voucher') }}"  autocomplete="name" autofocus>

                            @error('discount_voucher')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    
                    @guest
                    <div>
                        <small>*you could register by setting a password</small>
                        <div class="card card-body">
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

