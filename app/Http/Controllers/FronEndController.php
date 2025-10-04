<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderToItem;
use App\Models\Product\Item;
use Illuminate\Http\Request;

class FronEndController extends Controller
{
    public function product_list(Request $request)
    {
        $items = Item::select('id', 'item_name', 'price', 'description', 'type')->get()->map(function ($item) {
            $item->product_type = $item->type == 1 ? 'Main' : 'Bomper';
            return $item;
        });
        return response()->json($items);
    }

    public function add_user(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact' => 'nullable|string|max:15',
        ]);
        
        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => bcrypt($request->email), // default password
            'account_type' => 'User',
        ]);
        if (!$user) {
            return response()->json(['msgErr' => 'Failed to add user'], 500);
        }
        return response()->json([
            'msg' => 'User added successfully',
            'status' => 200,
            'userId' => $user->id,
        ]);
    }

    public function add_order(Request $request)
    {
        // validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['msgErr' => 'User not found'], 404);
        }

        // $paymentIntent = \Stripe\PaymentIntent::create([
        //     'amount' => $amount * 100, // in cents
        //     'currency' => 'usd',
        //     'customer' => $stripeCustomerId,
        //     'metadata' => [
        //         'user_id' => auth()->id(),
        //         'order_id' => $order->id ?? null
        //     ]
        // ]);


        // $order = Order::create([
        //     'user_id' => auth()->id(),
        //     'stripe_payment_intent_id' => $paymentIntent->id,
        //     'stripe_customer_id' => $paymentIntent->customer,
        //     'amount' => $paymentIntent->amount / 100,
        //     'currency' => $paymentIntent->currency,
        //     'status' => $paymentIntent->status, // succeeded, requires_payment_method, etc.
        //     'products' => json_encode($cartItems),
        //     'paid_at' => now(),
        // ]);

        $cartItems = [];
        foreach ($request->items as $itemRequest) {
            $item = Item::find($itemRequest['item_id']);
            if ($item) {
                $cartItems[] = [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'quantity' => $itemRequest['quantity'],
                    'price' => $item->price,
                ];
            }
        }
        $order = Order::create([
            'user_id' => $request->user_id,
            'stripe_payment_intent_id' => 24124,
            'stripe_customer_id' => 14214,
            'amount' => 100,
            'currency' =>'USD',
            'status' => 'succeeded', // succeeded, requires_payment_method, etc.
            'products' => json_encode($cartItems),
            'paid_at' => now(),
        ]);


        foreach ($request->items as $itemRequest) {
            $item = Item::find($itemRequest['item_id']);
            if ($item) {
                OrderToItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity' => $itemRequest['quantity'],
                    'price' => $item->price,
                ]);
            }
        }


        return response()->json([
            'msg' => 'Order placed successfully',
            'status' => 200,
            'orderId' => $order->id,
        ]);

       

    }

    public function create_checkout_session(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['msgErr' => 'User not found'], 404);
        }

        $lineItems = [];
        $totalAmount = 0;

        foreach ($request->items as $itemRequest) {
            $item = Item::find($itemRequest['id']);
            if ($item) {
                $amount = $item->price * $itemRequest['quantity'];
                $totalAmount += $amount;

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->item_name,
                            'description' => $item->description,
                        ],
                        'unit_amount' => intval($item->price * 100), // amount in cents
                    ],
                    'quantity' => $itemRequest['quantity'],
                ];
            }
        }

        if (empty($lineItems)) {
            return response()->json(['msgErr' => 'No valid items found'], 400);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $user->email,
                'success_url' => env('APP_URL') . '/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('APP_URL') . '/cancel',
            ]);

            return response()->json([
                'checkout_url' => $checkoutSession->url,
                'session_id' => $checkoutSession->id,
                'total_amount' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['msgErr' => 'Failed to create checkout session: ' . $e->getMessage()], 500);
        }
    }




}
