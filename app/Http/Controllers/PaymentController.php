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
            // Retrieve all the POST data sent by Razorpay
            $data = $request->all();
            dd($data);
            logger("Payment Callback Data: ", $data);

            // Extract necessary fields from the request
            $orderId = $data['razorpay_order_id'] ?? null;
            $paymentId = $data['razorpay_payment_id'] ?? null;
            $signature = $data['razorpay_signature'] ?? null;

            // Log any missing data
            if (!$orderId || !$paymentId || !$signature) {
                logger("Payment data missing: Order ID - $orderId, Payment ID - $paymentId, Signature - $signature");
                return redirect('/payment/error')->with('error', 'Payment data is missing.');
            }

            // Verify the Razorpay signature
            $api = new Api(config('payment.idfc.api_key'), config('payment.idfc.api_secret'));

            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // If verification succeeds, you can process the payment
            // Example: Update the order in the database and mark it as paid
            // $order = Order::where('order_id', $orderId)->first();
            // if ($order) {
            //     $order->payment_status = 'paid';
            //     $order->payment_id = $paymentId;
            //     $order->save();
            // }

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
