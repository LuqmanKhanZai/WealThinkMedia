<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FronEndController;



Route::middleware(['auth'])->group(function () {

});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('product-list', [FronEndController::class, 'product_list']);
Route::post('add-user', [FronEndController::class, 'add_user']);