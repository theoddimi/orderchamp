<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller {

     /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showAction(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {

        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart;
        } else {
            if ($request->session()->has('cart') && Cart::find($request->session()->get('cart')->id)) {
                $sessionCart = $request->session()->get('cart');
                $cart = Cart::find($sessionCart->id);
            } else {
                $cart = Cart::create();
            }
        }

        return view('checkout.show', [
            'products' => $cart->activeWithProducts,
            'cart' => $cart
        ]);
    }
}
