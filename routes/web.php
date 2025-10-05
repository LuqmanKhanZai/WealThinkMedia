<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripePaymentController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('order')->as('order.')->controller(OrderController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('print/{id}', 'print')->name('print');
        Route::get('get_data', 'get_data')->name('get_data');
    });

});

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'index');
    Route::post('stripe', 'stripe')->name('stripe.post');
});






