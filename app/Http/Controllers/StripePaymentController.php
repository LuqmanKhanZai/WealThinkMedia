<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function index()
    {
        return view('stripe');
    }

    public function stripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
      
        Charge::create ([
            "amount" => 10 * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Stripe Test Payment" 
        ]);
                
        return back()->with('success', 'Payment has been successful');
    }
}
