<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use Stripe\PaymentIntent;
use App\Models\OrderToItem;
use App\Models\Product\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


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



    public function sendUserToGHL(Request $request)
    {
        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6Im9KeWZRVnFyYm9zRUV6M2hJMTA2IiwiY29tcGFueV9pZCI6Ik43MDZ2NFBCeVJ5NG1lWndkTFE0IiwidmVyc2lvbiI6MSwiaWF0IjoxNzAxMDYzODgyMjg5LCJzdWIiOiJ1c2VyX2lkIn0.6PRSKmjDdJGuAZPQEJqMJ-AXZSDBJjn8djvrI_jqAgk';
        $url = 'https://rest.gohighlevel.com/v1/contacts/';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->post($url, [
            'firstName' => 'Jan',
            'lastName' => 'khan',
            'email' => 'fffff@gmail.com',
            'phone' => "121251251251",
            'tags'      => ['LaravelApp'],
        ]);

        if ($response->successful()) {
            return 'User successfully added to GoHighLevel';
        } else {
            return $response->body(); // debug response if fails
        }
    }



    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
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

        try {
            $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6Im9KeWZRVnFyYm9zRUV6M2hJMTA2IiwiY29tcGFueV9pZCI6Ik43MDZ2NFBCeVJ5NG1lWndkTFE0IiwidmVyc2lvbiI6MSwiaWF0IjoxNzAxMDYzODgyMjg5LCJzdWIiOiJ1c2VyX2lkIn0.6PRSKmjDdJGuAZPQEJqMJ-AXZSDBJjn8djvrI_jqAgk';
            $url    = 'https://rest.gohighlevel.com/v1/contacts/';

            Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept'        => 'application/json',
            ])->post($url, [
                'firstName' => $user->name,
                'email'     => $user->email,
                'phone'     => $user->contact,
                'tags'      => ['UserCreated'], // 👈 your tag here
            ]);
        } catch (\Exception $e) {
            // Optional: Log error if API fails
            Log::error('GHL User Add Failed: ' . $e->getMessage());
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
            'user_id'  => 'required',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
            'amount'    => 'required|numeric|min:1',
        ]);

        $getUser = User::find($request->user_id);

        if (!$getUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $amount = $request->amount;

        if (!$amount || !is_numeric($amount)) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));


        try {

            // $customer = \Stripe\Customer::create([
            //     'name' => $getUser->name,
            //     'email' => $getUser->email,
            // ]);
            if (!$getUser->stripe_customer_id) {
                $customer = \Stripe\Customer::create([
                    'name' => $getUser->name,
                    'email' => $getUser->email,
                ]);

                $getUser->stripe_customer_id = $customer->id;
                $getUser->save();
            } else {
                $customer = \Stripe\Customer::retrieve($getUser->stripe_customer_id);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // convert to cents
                'currency' => 'usd',
                // 'customer' => $getUser->createOrGetStripeCustomer()->id,
                'customer' => $customer->id,
                'automatic_payment_methods' => ['enabled' => true],
                'setup_future_usage' => 'off_session', // 🔥 tells Stripe to save card
            ]);

       
            $order = Order::create([
                'user_id' => $request->user_id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_customer_id' => $customer->id,
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

            try {
                $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6Im9KeWZRVnFyYm9zRUV6M2hJMTA2IiwiY29tcGFueV9pZCI6Ik43MDZ2NFBCeVJ5NG1lWndkTFE0IiwidmVyc2lvbiI6MSwiaWF0IjoxNzAxMDYzODgyMjg5LCJzdWIiOiJ1c2VyX2lkIn0.6PRSKmjDdJGuAZPQEJqMJ-AXZSDBJjn8djvrI_jqAgk';
                $url    = 'https://rest.gohighlevel.com/v1/contacts/';


                // Check if user exists in GHL
                $checkResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept'        => 'application/json',
                ])->get($url . '?email=' . urlencode($getUser->email));

                if ($checkResponse->successful() && !empty($checkResponse['contacts'])) {
                    $contactId = $checkResponse['contacts'][0]['id'];
                    $existingTags = $checkResponse['contacts'][0]['tags'] ?? [];

                    $updatedTags = array_unique(array_merge($existingTags, ['OrderPlaced']));

                    // Update existing GHL contact with new tag
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept'        => 'application/json',
                    ])->put($url . $contactId, [
                        'tags' => $updatedTags,
                    ]);
                } else {
                    // Create new contact if not found
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept'        => 'application/json',
                    ])->post($url, [
                        'firstName' => $getUser->name,
                        'email'     => $getUser->email,
                        'phone'     => $getUser->contact,
                        'tags'      => ['OrderPlaced'],
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('GHL Tag Update Failed: ' . $e->getMessage());
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

    // public function second_stripe_check(Request $request)
    // {
    //     $request->validate([
    //         'user_id'  => 'required',
    //         'payment_method' => 'required',
    //         'amount'    => 'required|numeric|min:1',
    //     ]);

    //     $paymentMethodId = $request->payment_method;

    //     if (!$paymentMethodId) {
    //         return response()->json(['error' => 'Payment method ID is required'], 400);
    //     }

    //     Stripe::setApiKey(config('services.stripe.secret'));
    //     $getUser = User::find($request->user_id);
    //     try {
    //         // // Attach payment method to customer (if not already)
    //         // $customer = \Stripe\Customer::create([
    //         //     'name' => $getUser->name,
    //         //     'email' => $getUser->email,
    //         // ]);

    //         $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
    //         $paymentMethod->attach(['customer' => $getUser->stripe_customer_id]);

    //         // \Stripe\PaymentMethod::attach($paymentMethodId, [
    //         //     'customer' => $getUser->stripe_customer_id,
    //         // ]);

    //         // Create PaymentIntent using the saved payment method
    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => $request->amount * 100,
    //             'currency' => 'usd',
    //             'customer' => $getUser->stripe_customer_id,
    //             'payment_method' => $paymentMethodId,
    //             'off_session' => false, // true if recurring/subscription style
    //             'confirm' => true, // charge immediately
    //         ]);

    //         return response()->json([
    //             'clientSecret' => $paymentIntent->client_secret,
    //             'msg' => 'Payment successful',
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }


    //     // $getUser = User::find($request->user_id);

    //     // if (!$getUser) {
    //     //     return response()->json(['error' => 'User not found'], 404);
    //     // }

    //     // if (!$getUser->stripe_customer_id) {
    //     //     return response()->json(['error' => 'Stripe customer ID not found for user'], 400);
    //     // }

    //     // Stripe::setApiKey(config('services.stripe.secret'));

    //     // try {
    //     //     // Attach the payment method to the customer
    //     //     $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method_id);
    //     //     $paymentMethod->attach(['customer' => $getUser->stripe_customer_id]);

    //     //     // Update user's default payment method
    //     //     $customer = \Stripe\Customer::retrieve($getUser->stripe_customer_id);
    //     //     $customer->invoice_settings = ['default_payment_method' => $paymentMethod->id];
    //     //     $customer->save();

    //     //     // Save payment method ID to user model
    //     //     $getUser->stripe_payment_method_id = $paymentMethod->id;
    //     //     $getUser->save();

    //     //     return response()->json([
    //     //         'msg' => 'Payment method attached and saved successfully',
    //     //         'payment_method_id' => $paymentMethod->id,
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'error' => $e->getMessage(),
    //     //     ], 500);
    //     // }
    // }

    public function second_stripe_check(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'amount'    => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $getUser = User::find($request->user_id);

        if (!$getUser || !$getUser->stripe_customer_id) {
            return response()->json(['error' => 'Stripe customer not found for this user'], 404);
        }

        $paymentMethodId = $request->payment_method;

        try {
            // ✅ Retrieve payment method
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            // ✅ Attach to customer if not already
            if ($paymentMethod->customer != $getUser->stripe_customer_id) {
                $paymentMethod->attach(['customer' => $getUser->stripe_customer_id]);
            }

            // ✅ Create and confirm PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // in cents
                'currency' => 'usd',
                'customer' => $getUser->stripe_customer_id,
                'payment_method' => $paymentMethodId,
                'off_session' => false, // user present
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never', // 🚀 this fixes your error
                ],
            ]);

            // ✅ Save order (optional, recommended)
            Order::create([
                'user_id' => $getUser->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_customer_id' => $getUser->stripe_customer_id,
                'amount' => $request->amount,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
                'paid_at' => now(),
            ]);


            try {
                $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6Im9KeWZRVnFyYm9zRUV6M2hJMTA2IiwiY29tcGFueV9pZCI6Ik43MDZ2NFBCeVJ5NG1lWndkTFE0IiwidmVyc2lvbiI6MSwiaWF0IjoxNzAxMDYzODgyMjg5LCJzdWIiOiJ1c2VyX2lkIn0.6PRSKmjDdJGuAZPQEJqMJ-AXZSDBJjn8djvrI_jqAgk';
                $url    = 'https://rest.gohighlevel.com/v1/contacts/';

                // 🔎 Check if user exists in GHL by email
                $checkResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept'        => 'application/json',
                ])->get($url . '?email=' . urlencode($getUser->email));

                if ($checkResponse->successful() && !empty($checkResponse['contacts'])) {
                    $contactId = $checkResponse['contacts'][0]['id'];
                    $existingTags = $checkResponse['contacts'][0]['tags'] ?? [];

                    $updatedTags = array_unique(array_merge($existingTags, ['UpsellPurchased']));

                    // 🏷️ Update existing GHL contact
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept'        => 'application/json',
                    ])->put($url . $contactId, [
                        'tags' => $updatedTags,
                    ]);
                } else {
                    // 🆕 Create new contact if not found
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept'        => 'application/json',
                    ])->post($url, [
                        'firstName' => $getUser->name,
                        'email'     => $getUser->email,
                        'phone'     => $getUser->contact,
                        'tags'      => ['UpsellPurchased'],
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('GHL Upsell Tag Error: ' . $e->getMessage());
            }

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'msg' => 'Payment successful',
                'status' => $paymentIntent->status,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    function sendOrUpdateGHLContact($user, $newTags = [])
    {
        $apiKey = 'YOUR_GOHIGHLEVEL_API_KEY';
        $baseUrl = 'https://rest.gohighlevel.com/v1/contacts/';

        // Step 1: Check if user already exists in GHL
        $checkResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->get($baseUrl . '?email=' . urlencode($user->email));

        $existingContact = null;
        if ($checkResponse->successful() && !empty($checkResponse['contacts'])) {
            $existingContact = $checkResponse['contacts'][0];
        }

        if ($existingContact) {
            // ✅ Contact exists → update tags
            $contactId = $existingContact['id'];

            $updatedTags = array_unique(array_merge($existingContact['tags'] ?? [], $newTags));

            $updateResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->put($baseUrl . $contactId, [
                'tags' => $updatedTags,
            ]);

            return $updateResponse->successful()
                ? "Updated GHL contact with new tags"
                : $updateResponse->body();

        } else {
            // 🚀 Contact doesn’t exist → create new one
            $createResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($baseUrl, [
                'firstName' => $user->first_name,
                'lastName'  => $user->last_name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'tags'      => $newTags,
            ]);

            return $createResponse->successful()
                ? "Created new GHL contact with tags"
                : $createResponse->body();
        }
    }






}
