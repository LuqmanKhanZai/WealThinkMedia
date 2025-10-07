<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FronEndController;



Route::middleware(['auth'])->group(function () {

});



Route::post('add-user', [FronEndController::class, 'add_user']);

Route::get('product-list', [FronEndController::class, 'product_list']);
Route::post('add-order', [FronEndController::class, 'add_order']);

Route::post('create-checkout-session', [FronEndController::class, 'create_checkout_session']);

Route::middleware('auth:sanctum')->post('payment-intent', [FronEndController::class, 'stripe_check']);
Route::middleware('auth:sanctum')->post('payment-intent-second', [FronEndController::class, 'second_stripe_check']);
