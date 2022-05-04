<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Discount;
use App\Models\Checkout;
use App\Jobs\ProcessRegisterCoupon;
use App\Http\Traits\CheckoutTrait;
use Illuminate\Foundation\Auth\User as AuthUser;

class CheckoutController extends Controller {

    use CheckoutTrait;
    
    const DEFAULT_DISCOUNT_ID = 1;
    const DISCOUNT_CREATE_DELAY_IN_MINUTES = 0;

    /**
     * 
     * @param int $checkoutId
     * @param Request $request
     * @param AuthUser $user
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function detailsShowAction($checkoutId, Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory 
    {
        $checkout = null;

        if (auth()->check()) {
            $checkout = Checkout::where('id', $checkoutId)->where('user_id', auth()->user()->id)->first();
        } else if ($request->session()->has('checkout-details')) {
            $sessionCheckout = $request->session()->get('checkout-details');
            $checkout = intval($checkoutId) === $sessionCheckout->id ? Checkout::find($sessionCheckout->id) : null;
        }

        return view('checkout.order-details', ['checkout' => $checkout]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeAction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $discount_id = null;
        $userDiscountPivot = null;
        $userRegistered = null;

        if (auth()->check()) {
            $this->validateForAuthUser($request->all(), auth()->user())->validate();
        } else {
            $this->validateForGuest($request->all())->validate();
        }
        
        # Register visitor if password given
        if ($request->has('password') && strlen($request->password) > 0) {
            $userRegistered = $this->createUserFromCheckout($request);
        }

        /**
         * Process checkout completion. We do nothing for now but empty user's cart
         * and update product stock
         * We also keep track of the discount
         */
        $cart = $this->getCartDecideForUserOrSession($request);

        if ($cart instanceof Cart) {
            $this->updateProductQuantityOrFail($cart);
        }

        # We also keep track of the discount
        if (auth()->check() && $request->has('discount_voucher') && strlen($request->discount_voucher) > 0) {
            $discountCodeFromForm = $request->discount_voucher;

            foreach (auth()->user()->activeDiscounts as $userDiscount) {

                if ($discountCodeFromForm === $userDiscount->pivot->token) {
                    $discount_id = $userDiscount->pivot->discount_id;
                    $userDiscountPivot = $userDiscount->pivot;
                }
            }
        }

        $checkout = new Checkout();

        $checkout->user_id = true === auth()->check() ? auth()->user()->id : null;
        $checkout->cart_id = $cart->id;
        $checkout->total_amount = $cart->getTotalAmount();

        if (null !== $discount_id) {
            $checkout->paid_amount = $cart->getTotalAmount() - Discount::find($discount_id)->amount;
            $checkout->discount = Discount::find($discount_id)->amount;
        } else {
            $checkout->paid_amount = $cart->getTotalAmount();
        }

        $checkout->save();

        if (null !== $userDiscountPivot) {
            $userDiscountPivot->active = 0;
            $userDiscountPivot->save();
        }
        
        # Create a discount  for user. Discount will be available 10 minutes from now
        if (auth()->user() instanceof User) {
            $this->createDiscountForUser(auth()->user());
        }

        $request->session()->forget('cart');
        $request->session()->put('checkout-details', $checkout);

        return redirect()->route('checkout.details', ['checkoutId' => $checkout->id]);
    }
    
    /**
     * 
     * @param \App\Models\User $user
     * @return void
     */
    private function createDiscountForUser(User $user): void
    {
        // Hardcoded discount for assignment needs. 
        // We could create a discount type class and search discount by type.
        $discount = Discount::find(self::DEFAULT_DISCOUNT_ID);
        ProcessRegisterCoupon::dispatch($discount, $user)
                ->delay(now()->addMinutes(self::DISCOUNT_CREATE_DELAY_IN_MINUTES));
    }

    /**
     * 
     * @param Request $request
     * @return Cart
     */
    private function getCartDecideForUserOrSession(Request $request): Cart 
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart;
        } else {
            $cart = $request->session()->get('cart');
            if (null !== $cart) {
                $cart = Cart::find($cart->id);
            }
        }

        return $cart;
    }

    /**
     * 
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse|Cart
     */
    private function updateProductQuantityOrFail(Cart $cart): \Illuminate\Http\RedirectResponse|Cart 
    {
        $productPivotInstances = [];
        $productInstances = [];

        foreach ($cart->activeWithProducts as $product) {
            if ($product->pivot->quantity > $product->quantity) {
                return redirect()
                                ->route('cart.show')
                                ->with(['error' => 'Max available quantity for product "' . $product->name . '" is ' . $product->quantity]);
            }

            $product->pivot->completed = 1;
            $product->quantity -= $product->pivot->quantity;
            $productPivotInstances[] = $product->pivot;
            $productInstances[] = $product;
        }

        for ($i = 0; $i < count($productPivotInstances); $i++) {
            $productInstances[$i]->save();
            $productPivotInstances[$i]->save();
        }

        return $cart;
    }
}
