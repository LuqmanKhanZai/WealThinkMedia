<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use Stripe\PaymentIntent;
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


    public function stripe_check(Request $request)
    {
        $user = $request->user_id; // Must be authenticated (using Laravel Sanctum or Passport)
        $packageId = $request->package_id;
        $getUser = User::find($user);
        Stripe::setApiKey(config('services.stripe.secret'));
        $packagePrice = PackageToPrice::where('package_id', $packageId)
            ->where('packageprice_status', 1)
            ->first();

        if (!$packagePrice) {
            response()->error('Active package price not found');
        }

        $amount = $packagePrice->amount;

        if (!$amount || !is_numeric($amount)) {
            response()->error('Invalid amount');
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // convert to cents
                'currency' => 'usd',
                'customer' => $getUser->createOrGetStripeCustomer()->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);
            // Calculate dates
            $startDate = now();
            $endDate = now()->addMonths($packagePrice->duration);

            UserToPackage::create([
                'user_id' => $getUser->user_id,
                'package_id' => $packageId,
                'packageprice_id' => $packagePrice->packageprice_id,
                'subscription_start_date' => $startDate,
                'subscription_end_date' => $endDate,
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

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
