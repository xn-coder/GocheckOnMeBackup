<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;
class PaypalController extends Controller
{
    /**
     * process transaction.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processTransactionpayyy(Request $request)
    {
        $amount = $request->amount;
        $request->session()->put('user_info', $request->all());
        try {   
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('successTransaction'),
                    "cancel_url" => route('cancelTransaction'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amount
                        ]
                    ]
                ]
            ]);
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return redirect()->away($link['href']);
                    }
                }

            } else {
                return redirect()->back()->with('success', 'please try again.');
                // return response()->json(['message'=>'Something went wrong please try again']);
            }
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage() ?? 'Something went wrong.');
            return redirect()->route('/');
        }
    }

    /**
     * success transaction.
     *
     * @return \Illuminate\Http\RedirectResponse
     */

  public function processTransaction(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'confirm_password' => 'required|same:password',
        'amount' => 'required|numeric',
        'status' => 'accepted',
        'status2' => 'accepted',
        'stripeToken' => 'required',
    ]);
    $request->session()->put('user_info', $request->only('name', 'email', 'password', 'amount'));

    try {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::create([
                'email' => $request->email,
                'name' => $request->name,
                'source' => $request->stripeToken,
            ]);

            // Create charge
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'customer' => $customer->id,
                'description' => 'Registration fee for ' . $request->name,
            ]);
        if ($charge->status === 'succeeded') {
            // Store payment ID in session for success transaction
            $request->session()->put('payment_id', $charge->id);
            
            // Return JSON response with redirect URL
            return response()->json([
                'success' => true,
                'redirect' => route('successTransaction')
            ]);
        }


        return response()->json([
            'success' => false,
            'error' => 'Payment not completed successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

//    public function successTransaction(Request $request)
// {
//     DB::beginTransaction();

//     try {
//         $user_info = $request->session()->get('user_info');

//         $provider = new PayPalClient;
//         $provider->setApiCredentials(config('paypal'));
//         $provider->getAccessToken();

//         $response = $provider->capturePaymentOrder($request['token']);

//         if (isset($response['status']) && $response['status'] == 'COMPLETED') {
//             $user = new User();
//             $user->name = $user_info['name'];
//             $user->email = $user_info['email'];
//             $user->password = Hash::make($user_info['password']);
//             $user->role = 2;
//             $user->save();

//             $payment = new Payment();
//             $payment->user_id = $user->id;
//             $payment->payment_id = $response['id'];
//             $payment->amount = $user_info['amount'];
//             $payment->status = 'success';
//             $payment->save();

//             DB::commit();
//             return redirect()->route('success');
//         } else {
//             throw new \Exception("Payment not completed successfully.");
//         }
//     } catch (\Throwable $throwable) {
//         DB::rollBack();
//         return response()->json(['message' => 'fail', 'error' => $throwable->getMessage()]);
//     }
// }

//     *
//      * cancel transaction.
//      *
//      * @return \Illuminate\Http\RedirectResponse
     
//     public function cancelTransaction(Request $request)
// {
//     DB::beginTransaction();
//     try {
//         $provider = new PayPalClient;
//         $provider->setApiCredentials(config('paypal'));
//         $provider->getAccessToken();
//         $response = $provider->capturePaymentOrder($request->input('token'));
//         $user_info = $request->session()->get('user_info');
//         if (isset($response['error'])) {
//             $errorDetails = $response['error'];
//             if ($errorDetails['name'] === 'UNPROCESSABLE_ENTITY' && $errorDetails['details'][0]['issue'] === 'ORDER_NOT_APPROVED') {
//                 $payment = new Payment();
//                 $payment->payment_id = $request->input('token');
//                 $payment->amount = $user_info['amount']; 
//                 $payment->status = 'fail';
//                 $payment->save();
//                 DB::commit();
//                 return redirect()->route('failed')->with('error', 'Payment not approved by user.');
//             }
//             return redirect()->route('failed')->with('error', 'An error occurred: ' . $errorDetails['message']);
//         }

//     } catch (\Exception $e) {
//         return redirect()->route('failed')->with('error', $e->getMessage());
//     }
// }

public function successTransaction(Request $request)
{
    DB::beginTransaction();

    try {
        $user_info = $request->session()->get('user_info');
        $payment_id = $request->session()->get('payment_id');

        if (!$user_info || !$payment_id) {
            return redirect()->route('failed')->with('error', 'Session data missing. Please try again.');
        }

        // Check if user already exists
        $existingUser = User::where('email', $user_info['email'])->first();
        if ($existingUser) {
            return redirect()->route('failed')->with('error', 'User with this email already exists.');
        }

        // Create user
        $user = new User();
        $user->name = $user_info['name'];
        $user->email = $user_info['email'];
        $user->password = Hash::make($user_info['password']);
        $user->role = 2;
        $user->save();

        // Create payment record
        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->payment_id = $payment_id;
        $payment->amount = $user_info['amount'];
        $payment->status = 'success';
        $payment->save();

        DB::commit();
        
        // Clear session data
        $request->session()->forget(['user_info', 'payment_id']);
        
        return redirect()->route('success')->with('success', 'Registration and payment successful!');

    } catch (\Throwable $throwable) {
        DB::rollBack();
        return redirect()->route('failed')->with('error', $throwable->getMessage());
    }
}


public function cancelTransaction(Request $request)
{
    DB::beginTransaction();

    try {
            $payment_id = $request->input('payment_id');
            
            if ($payment_id) {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                
                // Create refund
                $refund = \Stripe\Refund::create([
                    'charge' => $payment_id,
                ]);
                
                // Update payment status if record exists
                $payment = Payment::where('payment_id', $payment_id)->first();
                if ($payment) {
                    $payment->status = 'refunded';
                    $payment->save();
                }
            }
            
            return redirect()->route('failed')->with('error', 'Payment was canceled.');
            
        } catch (\Exception $e) {
            return redirect()->route('failed')->with('error', $e->getMessage());
        }
}



    public function success(){
        return view('front.success');
    }

    public function failed(){
        return view('front.payment_failed');
    }
}