<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();
Route::post('/product/add-to-cart', [ProductController::class, 'addToCartAction'])->name('cart.product.store');
Route::post('/product/remove-from-cart', [ProductController::class, 'removeFromCart'])->name('cart.product.delete');
Route::get('/checkout/cart', [CartController::class, 'showAction'])->name('cart.show');
Route::post('/checkout/complete', [CheckoutController::class, 'completeAction'])->name('checkout.complete');
Route::get('/checkout/{checkoutId}/details', [CheckoutController::class, 'detailsShowAction'])->name('checkout.details');

