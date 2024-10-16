<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

class PaymentController extends Controller
{

    public function initiatePayment(Request $request)
    {
        $url = 'https://api.razorpay.com/v1/orders';

        $data = [
            "amount" => $request->amount * 100,
            "currency" => "INR",
            "receipt" => "Receipt no. 1",
            "notes" => [
                "notes_key_1" => "Tea, Earl Grey, Hot",
                "notes_key_2" => "Tea, Earl Grey… decaf."
            ]
        ];

        $response = Http::withBasicAuth(config('payment.idfc.api_key'), config('payment.idfc.key_secret'))
            ->post($url, $data);

        if ($response->successful()) {
            // Handle successful response
            $arrayData = $response->json();
            return view('checkout', compact('arrayData'));
        } else {
            // Handle error response
            return response()->json($response->json(), $response->status());
        }
    }
    public function paymentCallback(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required',
            ]);
    
            $input = $request->all();
            $api = new Api(config('payment.idfc.api_key'), config('payment.idfc.key_secret'));
           
                $payment = $api->payment->fetch($input['order_id']);
            return redirect('/payment/success')->with('status', 'Payment successful!');
        } catch (\Exception $e) {
            logger("Razorpay Signature Verification Failed: " . $e->getMessage());
            return redirect('/payment/error')->with('error', 'Payment verification failed.');
        }
    }

    public function paymentError()
    {
        return "error";
        //return view('payment_error');
    }
    public function paymentSuccess()
    {
        return "success";
        //return view('payment_success');
    }
}
