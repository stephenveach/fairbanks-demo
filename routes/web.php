<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::statamic('products', 'products/index', [
    'title' => 'Products',
]);

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{itemId}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{itemId}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'start'])->name('checkout.start');
Route::get('/checkout/complete', [CheckoutController::class, 'complete'])->name('checkout.complete');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::any('adminer', '\Aranyasen\LaravelAdminer\AdminerAutologinController@index');
