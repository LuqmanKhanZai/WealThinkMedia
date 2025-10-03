<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\ProfileController;
use App\Http\Controllers\UserManagement\RolePrivilagesController;
use App\Http\Controllers\UserManagement\UserPrivilagesController;

Route::middleware(['auth'])->group(function () {

    Route::prefix('user')->as('user.')->controller(UserController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', 'get_data')->name('get_data');
    });

    Route::prefix('role')->as('role.')->controller(RoleController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', 'get_data')->name('get_data');
    });

    Route::prefix('role_privilages')->as('role_privilages.')->controller(RolePrivilagesController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('permission_list', 'permission_list')->name('permission_list');
        Route::post('assign_permission', 'assign_permission')->name('assign_permission');
        Route::get('get_permission', 'get_permission')->name('get_permission');

        Route::post('store', 'store')->name('store');
        Route::post('status', 'status')->name('status');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('delete/{id}', 'destroy')->name('delete');
        Route::get('get_data', 'get_data')->name('get_data');
    });

    Route::prefix('user_privilages')->as('user_privilages.')->controller(UserPrivilagesController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('permission_list', 'permission_list')->name('permission_list');
        Route::post('assign_permission', 'assign_permission')->name('assign_permission');
        Route::get('get_permission', 'get_permission')->name('get_permission');
    });

    Route::prefix('profile')->as('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
    });
});