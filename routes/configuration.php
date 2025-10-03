<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ItemController;
use App\Http\Controllers\Configuration\CityController;
use App\Http\Controllers\Configuration\CategoryController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('category')->as('category.')->controller(CategoryController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', action: 'get_data')->name('get_data');
    });

    Route::prefix('city')->as('city.')->controller(CityController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::post('modal', 'modal')->name('modal');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', 'get_data')->name('get_data');
    });

    Route::prefix('item')->as('item.')->controller(ItemController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', 'get_data')->name('get_data');
        Route::post('import', 'import')->name('import');
        Route::get('export', 'export')->name('export');
    });
});

