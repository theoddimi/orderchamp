<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

trait CheckoutTrait {

    use RegistersUsers;

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

//    protected function validatorCheckoutFields(array $data) {
//        return Validator::make($data, [
//                    'name' => ['required', 'string', 'max:255'],
//                    'address' => ['required', 'string', 'max:255'],
//                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//                    'phone_number' => ['required', 'string', 'min:10'],
//        ]);
//    }
    
    private function validateForAuthUser(array $data, User $user) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'delivery_address' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'in:' . $user->email],
                    'phone_number' => ['required', 'string', 'min:10'],
        ]);
    }
    
    private function validateForGuest(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'delivery_address' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'phone_number' => ['required', 'string', 'min:10'],
        ]);
    }

    protected function createUserFromCheckout(Request $request) {
        $this->validator($request->all())->validate();
        $sessionCart = $request->session()->get('cart');

        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);

        $cart = Cart::find($sessionCart->id);
        $cart->user()->associate($user);
        $cart->save();

        if ($request->has('delivery_address') || $request->has('phone_number')) {
            $userDetails = new \App\Models\UserDetails(
                    [
                'delivery_address' => $request->delivery_address ?? '',
                'phone_number' => $request->phone_number ?? ''
                    ]
            );
            $user->details()->save($userDetails);
        }

        return $user;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
        ]);
    }
}
