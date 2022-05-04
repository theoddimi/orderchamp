<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class ProductController extends Controller {

    const ERROR_STOCK_UNAVAILABLE = 'Product is out of stock!';

    public function __construct() {
        //  $this->middleware('auth');
    }

    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCartAction(Request $request): \Illuminate\Http\RedirectResponse 
    {
        $productId = $request->product_id;
        $product = Product::find($productId);

        if ($product->quantity < 1) {
            return redirect()->route('home')->with('error', self::ERROR_STOCK_UNAVAILABLE);
        }

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->cart()->exists()) {
                $cart = $user->cart;
            } else {
                $cart = Cart::create(['user_id' => $user->id]);
            }
        } else {
            if ($request->session()->has('cart') && Cart::find($request->session()->get('cart')->id)) {
                $cart = $request->session()->get('cart');
            } else {
                $cart = Cart::create();
            }
        }

        $cart = $this->addOrUpdateExistingProductToCart($cart, $product);
        $request->session()->put('cart', $cart);

        return redirect()->route('home');
    }

    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromCart(Request $request): \Illuminate\Http\RedirectResponse 
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart;
        } else {
            $cart = $request->session()->get('cart');
        }

        if ($request->has('product_id')) {
            $product = Product::find($request->product_id);
            $cartProducts = $cart->activeWithProducts()->where('product_id', $product->id)->first();
            $cartProducts->pivot->delete();
        }

        return redirect()->route('cart.show');
    }

    /**
     * 
     * @param Cart $cart
     * @param Product $product
     * @return Cart
     */
    private function addOrUpdateExistingProductToCart(Cart $cart, Product $product): Cart
    {
        if (true === $cart->activeWithProducts()->where('product_id', $product->id)->exists()) {
            $product = $cart->activeWithProducts()->where('product_id', $product->id)->first();

            $cart->activeWithProducts()->updateExistingPivot(
                    $product->id,
                    ['quantity' => ++$product->pivot->quantity]
            );
        } else {
            $cart->activeWithProducts()->attach($product, ['quantity' => 1]);
        }

        return $cart;
    }

    /**
     * 
     * @param Cart $cart
     * @param Request $request
     * @return void
     */
    private function updateCartProductPivot(Cart $cart, Request $request): void 
    {
        $product = Product::find($request->product_id);
        $product = $cart->activeWithProducts()->where('product_id', $product->id)->first();

        if ($request->has('delete_quantity') && $request->delete_quantity > 0) {

            if ($product->pivot->quantity - $request->delete_quantity >= 0) {
                $cart->activeWithProducts()->updateExistingPivot(
                        $product->id,
                        ['quantity' => $product->pivot->quantity - $request->delete_quantity]
                );
            } else {
                $cart->activeWithProducts()->updateExistingPivot(
                        $product->id,
                        ['quantity' => 0]
                );
            }
        } else {
            $product->pivot->delete();
        }
    }
}
