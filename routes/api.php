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
Route::post('add-order', [FronEndController::class, 'add_order']);

Route::post('create-checkout-session', [FronEndController::class, 'create_checkout_session']);
// Route::post('webhook', [FronEndController::class, 'handle_webhook']);
// Route::get('order-success', [FronEndController::class, 'order_success']);
// Route::get('order-cancel', [FronEndController::class, 'order_cancel']);
// Route::get('my-orders', [FronEndController::class, 'my_orders']);
// Route::get('order-details/{id}', [FronEndController::class, 'order_details']);
// Route::post('initiate-refund', [FronEndController::class, 'initiate_refund']);
// Route::get('download-invoice/{id}', [FronEndController::class, 'download_invoice']);
// Route::get('generate-invoice/{id}', [FronEndController::class, 'generate_invoice']);
// Route::get('send-invoice/{id}', [FronEndController::class, 'send_invoice']);
// Route::get('all-invoices', [FronEndController::class, 'all_invoices']);
// Route::get('invoice-details/{id}', [FronEndController::class, 'invoice_details']);
// Route::get('send-invoice-email/{id}', [FronEndController::class, 'send_invoice_email']);
// Route::get('all-refunds', [FronEndController::class, 'all_refunds']);
// Route::get('refund-details/{id}', [FronEndController::class, 'refund_details']);
// Route::get('send-refund-email/{id}', [FronEndController::class, 'send_refund_email']);
// Route::get('all-users', [FronEndController::class, 'all_users']);
// Route::get('user-details/{id}', [FronEndController::class, 'user_details']);