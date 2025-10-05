<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use Stripe\PaymentIntent;
use App\Models\OrderToItem;
use App\Models\Product\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // public function add_user(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'contact' => 'required|string|max:15',
    //     ]);

    //     $checkUser = User::where('email', $request->email)->first();    
    //     if ($checkUser) {
    //     $token = Auth::user()->createToken('huma-app')->plainTextToken;
    //        return response()->json([
    //             'msg' => 'User already added',
    //             'status' => 200,
    //             'userId' => $checkUser->id,
    //         ]);
    //     }
        
    //     // create user
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'contact' => $request->contact,
    //         'password' => bcrypt($request->email), // default password
    //         'account_type' => 'User',
    //     ]);
    //     if (!$user) {
    //         return response()->json(['msgErr' => 'Failed to add user'], 500);
    //     }
    //     return response()->json([
    //         'msg' => 'User added successfully',
    //         'status' => 200,
    //         'userId' => $user->id,
    //         'token' => $user->createToken('wealthink')->plainTextToken,
    //     ]);
    // }


    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'contact' => 'required|string|max:15',
        ]);

        // Check if user exists
        $checkUser = User::where('email', $request->email)->first();

        if ($checkUser) {
            // Generate token for existing user
            $token = $checkUser->createToken('wealthink')->plainTextToken;

            return response()->json([
                'msg' => 'User already exists',
                'status' => 200,
                'userId' => $checkUser->id,
                'token' => $token,
            ]);
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => bcrypt($request->email), // default password same as email
            'account_type' => 'User',
        ]);

        if (!$user) {
            return response()->json([
                'msgErr' => 'Failed to add user',
                'status' => 500,
            ], 500);
        }

        // Generate token for new user
        $token = $user->createToken('wealthink')->plainTextToken;

        return response()->json([
            'msg' => 'User added successfully',
            'status' => 200,
            'userId' => $user->id,
            'token' => $token,
        ]);
    }


    public function add_order(Request $request)
    {
        // validation
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
            'amount'    => 'required|numeric|min:1',
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
            'amount' => $request->amount,
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
                    'item_id'  => $item->id,
                    'quantity' => $itemRequest['quantity'],
                    'price'    => $itemRequest['price'], // snapshot price
                ]);
            }
        }
        return response()->json([
            'msg' => 'Order placed successfully',
            'status' => 200,
            'orderId' => $order->id,
        ]);

    }

    public function stripe_check(Request $request)
    {

        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
            'amount'    => 'required|numeric|min:1',
        ]);

        $user = $request->user_id; // Must be authenticated (using Laravel Sanctum or Passport)
        $getUser = User::find($user);
        
        if (!$getUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $amount = $request->amount;

        if (!$amount || !is_numeric($amount)) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));


        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // convert to cents
                'currency' => 'usd',
                'customer' => $getUser->createOrGetStripeCustomer()->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

       
            $order = Order::create([
                'user_id' => $request->user_id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_customer_id' => $paymentIntent->customer,
                'amount' => $request->amount,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status, // succeeded, requires_payment_method, etc.
                'products' => null,
                'paid_at' => now(),
            ]);


            foreach ($request->items as $itemRequest) {
                $item = Item::find($itemRequest['item_id']);
                if ($item) {
                    OrderToItem::create([
                        'order_id' => $order->id,
                        'item_id'  => $item->id,
                        'quantity' => $itemRequest['quantity'],
                        'price'    => $itemRequest['price'], // snapshot price
                    ]);
                }
            }
           

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'msg' => 'Payment intent created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }




}
