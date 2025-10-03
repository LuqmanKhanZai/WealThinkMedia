<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripePaymentController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'index');
    Route::post('stripe', 'stripe')->name('stripe.post');
});

